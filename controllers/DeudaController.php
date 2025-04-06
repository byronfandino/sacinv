<?php

namespace Controllers;

use Mpdf\Mpdf;
use Model\Cliente;
use Model\ClienteAPI;
use MVC\Router;
use Model\Departamento;
use Model\Deuda;
use Model\DeudaMovimiento;
use Model\DeudoresReporte;

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '512M');

require_once '../includes/parameters.php';
header("Access-Control-Allow-Origin: " . $urlJSON); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");

date_default_timezone_set("America/Bogota"); // Configurar la zona horaria de Colombia

class DeudaController{
    
    public static function inicio(Router $router){
        
        $departamentos = Departamento::all('nombre_depart');

        $router->renderIndex('deudores/index', [
            'departamentos' => $departamentos
        ]);
    }

    //Obtener todos los datos del cliente con el id, utilizado para crear el reporte
    public static function getCliente($id){
        $query = "SELECT id_cliente, cedula_nit, nombre, telefono, direccion, email, fk_ciudad, nombre_ciudad, cod_depart, nombre_depart FROM cliente as cl, ciudad as cd, departamento as dp WHERE cl.fk_ciudad = cd.id_ciudad AND dp.cod_depart = cd.codigo_depart AND id_cliente = " . $id . " ORDER BY nombre";
        $clienteFound = ClienteAPI::SQL($query);
        return isset($clienteFound[0]) ? $clienteFound[0] : null; //Retorna un arreglo y por lo tanto se indica la primera posición para que retorne el objeto
    }

    //Obtener la llave primaria de la deuda según el último registro del cliente, para buscar sus movimientos
    public static function getClienteDeuda($fk_cliente){

        $query = "SELECT * FROM deuda WHERE fk_cliente = " . $fk_cliente . " ORDER BY id_deuda DESC LIMIT 1";
        $clienteFound = Deuda::SQL($query);
        return isset($clienteFound[0]) ? $clienteFound[0] : null; //Retorna un arreglo y por lo tanto se indica la primera posición para que retorne el objeto
    }

    //Obtener el último movimiento del cliente, para saber si el saldo es 0
    //Si el saldo es 0 se crea un nuevo registro en la tabla deudores y se usa como llave foránea en la tabla deuda_movimiento
    public static function getUltimoMovimiento($fk_deuda){
        $query = "SELECT * FROM deuda_movimiento WHERE fk_deuda = " . $fk_deuda . " ORDER BY id_mov DESC LIMIT 1";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return isset($mov_deuda[0]) ? $mov_deuda[0] : null; //Retornamos únicamente el objeto
    }

    //Se obtiene solo es registro completo del movimiento de la deuda, para actualizar los datos, o para eliminar el registro
    public static function getMovimientoDeuda($id_mov){
        $query = "SELECT * FROM deuda_movimiento WHERE id_mov = " . $id_mov . " LIMIT 1";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return isset($mov_deuda[0]) ? $mov_deuda[0] :  null; //Retornamos únicamente el objeto
    }

    //Se obtiene todos los registros posteriores al id, con el fin de actualizar el saldo de cada registro, siempre y cuando se haya actualizado o eliminado aquel registro por el cual se está realizando esta consulta
    public static function getMovimientosUpdate($fk_deuda, $id_mov){
        $query = "SELECT * FROM deuda_movimiento WHERE fk_deuda = " . $fk_deuda . " AND id_mov > " . $id_mov . " ORDER BY id_mov ASC";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return $mov_deuda; //Retornamos el arreglo
    }

    //Se obtiene únicamente el registro anterior de deuda_movimiento para actualizar el saldo en los registros posteriores 
    public static function getMovimientoAnterior($fk_deuda, $id_mov){
        $query = "SELECT * FROM deuda_movimiento WHERE id_mov < " . $id_mov . " AND fk_deuda = " . $fk_deuda . " ORDER BY id_mov DESC LIMIT 1";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return isset($mov_deuda[0]) ? $mov_deuda[0] : null; //Retornamos únicamente el objeto        
    }

    //Obtenemos la consulta de los clientes deudores actuales para crear un reporte en pdf
    public static function getDeudores(){
        $query = "SELECT c.nombre AS nombre_cliente, dm.saldo, dm.fecha FROM deuda_movimiento dm JOIN ( SELECT DISTINCT ON (deuda_movimiento.fk_deuda) deuda_movimiento.fk_deuda, deuda_movimiento.id_mov FROM deuda_movimiento ORDER BY deuda_movimiento.fk_deuda, deuda_movimiento.id_mov DESC) filtro ON dm.fk_deuda = filtro.fk_deuda AND dm.id_mov = filtro.id_mov JOIN deuda d ON dm.fk_deuda = d.id_deuda JOIN cliente c ON d.fk_cliente = c.id_cliente WHERE dm.saldo <> 0::double precision AND c.nombre::text <> 'Usuario de prueba'::text ORDER BY c.nombre";
        $mov_deuda = DeudoresReporte::SQL($query);
        return $mov_deuda; //Se retorna el arreglo
    }

    //Obtenemos la consulta de todos los movimientos de la deuda pendiente del cliente para crear un reporte en pdf
    public static function getTotalMovimientosDeudor($id){
        $sql ="SELECT id_mov, fk_deuda, tipo_mov, descripcion, cant, valor_unit, valor_total, saldo, fecha, hora FROM deuda_movimiento WHERE fk_deuda = ( SELECT id_deuda FROM deuda WHERE fk_cliente = " . (int) $id . " ORDER BY id_deuda DESC LIMIT 1) ORDER BY id_mov ASC OFFSET 1";
        $deuda_mov = DeudaMovimiento::SQL($sql);
        return $deuda_mov;
    }

    public static function guardar(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $clienteInDeuda = self::getClienteDeuda((int) $_POST['fk_cliente']);
            
            if (empty($clienteInDeuda)){
                //La deuda no existe y hay que crearla
                $deuda = new Deuda($_POST);
                $alertas = $deuda->validar();

                if(empty($alertas) && $_POST['tipo_mov'] === 'D'){
                    
                    //Se crea el nuevo registro
                    $resultado = $deuda->crear();
                    
                    if($resultado === true){
                        //obtenemos el objeto que contiene el id de la deuda
                        $deuda = self::getClienteDeuda((int) $_POST['fk_cliente']);

                        // En la variable $clienteInDeuda ya obtenemos el id_deuda para agregarlo al movimiento_deuda

                        /** @var Deuda $deuda */
                        $fk_deuda = $deuda->getIdDeuda();

                        //Se agrega un NUEVO REGISTRO de deuda_movimiento
                        $fecha_actual = date("Y-m-d"); // Obtiene la fecha actual 
                        $hora_actual = date("H:i"); // Obtiene la hora actual 

                        // Creamos un objeto arreglo asociativo nuevo 
                        $newObjectMov = [
                            "fk_deuda" => $fk_deuda,
                            "tipo_mov" => "A",
                            "descripcion" => "Inicio",
                            "cant" => 1,
                            "valor_unit" => 0,
                            "valor_total" => 0,
                            "saldo" => 0,
                            "fecha" => $fecha_actual,
                            "hora" => $hora_actual,
                        ];

                        $deudaMovimiento = new DeudaMovimiento($newObjectMov);
                        $resultado = $deudaMovimiento->crear();

                        if($resultado == true){

                            //Agregamos la llave foránea al POST
                            $_POST['fk_deuda'] = $fk_deuda;
                            $_POST['saldo'] = (int) $_POST['valor_total'];

                            $deudaMovimiento = new DeudaMovimiento($_POST);
                            $alertas = $deudaMovimiento->validar();

                            if(empty($alertas)){

                                $resultado = $deudaMovimiento->crear();

                                if ($resultado === true ){
                                    echo json_encode([
                                        "rta" => "true",
                                        "message" => "Registro agregado"
                                    ]);
                                }else{
                                    echo json_encode([
                                        "rta" => "false",
                                        "message" => "El cliente es Nuevo por lo tanto La Deuda Nueva fue creada, el inicio del movimiento de la deuda con saldo 0 fue creado, pero  pero el Movimiento de la deuda con los datos actuales no pudo crearse", 
                                        "error" => $resultado
                                    ]);
                                }

                            }else{
                                echo json_encode([
                                    "rta" => "false",
                                    "message" => "Se agregó la NUEVA deuda pero no pasó la validación de campos del Movimiento de la deuda", 
                                    "alertas" => $alertas
                                ]);
                            }
                        }else{
                            echo json_encode([
                                "rta" => "false",
                                "message" => "La Deuda fue creada pero el Movimiento de la deuda no pudo crearse", 
                                "error" => $resultado
                            ]);
                        }
                    }else{
                        echo json_encode([
                            "rta" => "false",
                            "message" => "La deuda es Nueva y no pudo crearse", 
                            "error" => $resultado
                        ]);
                    }

                }else{

                    if (!empty($alertas)){
                        echo json_encode([
                            "rta" => "false",
                            "message" => "La deuda no existe pero no pasó la validación de campos", 
                            "alertas" => $alertas
                        ]);
                    }

                    if ($_POST['tipo_mov'] !== "D"){
                        echo json_encode([
                            "rta" => "false",
                            "message" => "No se puede guardar un abono, porque el cliente no tiene deuda"
                        ]);
                    }
                }

            }else{
                //El cliente Existe en la tabla deuda y hay insertar un registro nuevo verificndo el saldo
                //obtenemos el id de la deuda guardado

                /** @var Deuda $clienteInDeuda */
                $fk_deuda = $clienteInDeuda->getIdDeuda();

                //Obtenemos el registro con el último saldo registrado
                $deuda_mov = self::getUltimoMovimiento($fk_deuda);

                //Obtenemos el último saldo
                /** @var DeudaMovimiento $deuda_mov */
                $lastSaldo = $deuda_mov->getSaldo();

                if ($lastSaldo == 0){

                    if ($_POST['tipo_mov'] == "D"){

                        //Se crea un nuevo regitro en la tabla deuda
                        $deuda = new Deuda($_POST);
                        $resultado = $deuda->crear();
    
                        if ($resultado === true){
                            
                            //Obtenemos el ultimo registro de la deuda
                            $clienteInDeuda = self::getClienteDeuda((int) $_POST['fk_cliente']);
    
                            /** @var Deuda $clienteInDeuda */
                            $fk_deuda = $clienteInDeuda->getIdDeuda();
                            
                            //Se agrega un NUEVO REGISTRO de deuda_movimiento
    
                            $fecha_actual = date("Y-m-d"); // Obtiene la fecha actual 
                            $hora_actual = date("H:i"); // Obtiene la hora actual 
    
                            /*Se ingresa un registro en blanco a la tabla Movimiento de la Deuda*/
                            // Creamos un objeto arreglo asociativo nuevo 
                            $newObjectMov = [
                                "fk_deuda" => $fk_deuda,
                                "tipo_mov" => "A",
                                "descripcion" => "Inicio",
                                "cant" => 1,
                                "valor_unit" => 0,
                                "valor_total" => 0,
                                "saldo" => 0,
                                "fecha" => $fecha_actual,
                                "hora" => $hora_actual,
                            ];
    
                            $deudaMovimiento = new DeudaMovimiento($newObjectMov);
                            $resultado = $deudaMovimiento->crear();
    
                            if($resultado == true){
    
                                //Agregamos la llave foránea al POST
                                $_POST['fk_deuda'] = $fk_deuda;
                                $_POST['saldo'] = (int) $_POST['valor_total'];
    
                                $deudaMovimiento = new DeudaMovimiento($_POST);
                                $alertas = $deudaMovimiento->validar();
    
                                if(empty($alertas)){
                                    
                                    $resultado = $deudaMovimiento->crear();
    
                                    if ($resultado === true ){
                                        echo json_encode([
                                            "rta" => "true",
                                            "message" => "Registro agregado"
                                        ]);
                                    }else{
                                        echo json_encode([
                                            "rta" => "false",
                                            "message" => "El cliente ya existía, La Deuda Nueva fue creada, el inicio del movimiento de la deuda con saldo 0 fue creado, pero  pero el Movimiento de la deuda con los datos actuales no pudo crearse", 
                                            "error" => [$_POST, $resultado]
                                        ]);
                                    }
    
                                }else{
                                    echo json_encode([
                                        "rta" => "false",
                                        "message" => "El cliente ya existía, se agregó la NUEVA deuda pero no pasó la validación de campos del Movimiento de la deuda", 
                                        "alertas" => $alertas
                                    ]);
                                }
                            }else{
                                echo json_encode([
                                    "rta" => "false",
                                    "message" => "Se detectó que el cliente ya existía en la Deuda, por lo tanto se creó un nuevo registro porque el saldo estaba en 0, pero no se pudo crear el nuevo registro en el Movimiento de la deuda como saldo inicial en 0", 
                                    "error" => $resultado
                                ]);
                            }
    
                        }else{
    
                            echo json_encode([
                                "rta" => "false",
                                "message" => "Ya existía un registro en en Deuda, y como el saldo es de 0, Se intentó crear un nuevo registro en la Deuda pero no fue psoble", 
                                "error" => $resultado
                            ]);
                        }
                    }else{
                        echo json_encode([
                            "rta" => "false",
                            "message" => "No es posible comenzar con un abono debido a que el cliente no tiene deuda"
                        ]);
                    }

                }else{
                    
                    //Se guarda el movimiento 
                    //Actualizamos el arreglo POST
                    $_POST['fk_deuda'] = $fk_deuda;
                    
                    //Se verifica el tipo de movimiento
                    if ($_POST['tipo_mov'] == "D"){
                        $_POST['saldo'] = (int) $lastSaldo + (int) $_POST['valor_total'];
                    }else{
                        $_POST['saldo']= (int) $lastSaldo - (int) $_POST['valor_total'];
                    }
    
                    //Se crea el objeto
                    $deuda_mov = new DeudaMovimiento($_POST);
                    $alertas = $deuda_mov->validar();
    
                    if(empty($alertas)){
                        
                        $resultado = $deuda_mov->crear();
    
                        if($resultado === true){
                            echo json_encode([
                                "rta" => "true",
                                "message" => "Registro agregado"
                            ]);
                        }else{
    
                            echo json_encode([
                                "rta" => "false",
                                "message" => "La deuda ya existía, pero No se agregó el Movimiento de la deuda", 
                                "error" => $resultado
                            ]);
                        }
    
                    }else{
                        echo json_encode([
                            "rta" => "false",
                            "message" => "La deuda ya existía pero No pasó la validación de campos del Movimiento de la deuda", 
                            "alertas" => $alertas
                        ]);
                    }
                }
            }
        }
    }
    
    public static function actualizar(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Obtener el movimiento actual de la base de datos
            $get_mov = self::getMovimientoDeuda((int) $_POST['id_mov']);

            /** @var DeudaMovimiento $get_mov */
            $_POST['fk_deuda'] = (int) $get_mov->getFkDeuda();
            $_POST['fecha'] = date("Y-m-d"); // Obtiene la fecha actual 
            $_POST['hora'] = date("H:i"); // Obtiene la hora actual

            // Consultamos el saldo del registro anterior
            /** @var DeudaMovimiento $get_mov_ant */
            $get_mov_ant = self::getMovimientoAnterior((int) $get_mov->getFkDeuda(), (int) $_POST['id_mov']);
            $saldo_anterior = $get_mov_ant->saldo;

            if ($_POST['tipo_mov'] == "D"){
                $_POST['saldo'] = (int) $saldo_anterior + (int) $_POST['valor_total'];
            }else{
                /** @var DeudaMovimiento $get_mov_ant */
                $_POST['saldo'] = (int) $saldo_anterior - (int) $_POST['valor_total'];
            }

            $movimiento = new DeudaMovimiento($_POST);
            $alertas = $movimiento->validar();

            if(empty($alertas)){

                //Actualizamos el movimiento
                $resultado = $movimiento->actualizar();

                if($resultado === true){

                    //Se procede a modificar todos los demás registros siguientes
                    $array_mov = self::getMovimientosUpdate($movimiento->getFkDeuda(), $movimiento->getIdMov());
                    
                    if (!empty($array_mov)){
                        
                        $nuevoSaldo = (int) $movimiento->getSaldo();

                        foreach($array_mov as $objeto){
    
                            /** @var DeudaMovimiento $objeto */
                            if ($objeto->tipo_mov == "D"){
                                $nuevoSaldo = $nuevoSaldo + (int) $objeto->valor_total;
                            }else{
                                $nuevoSaldo = $nuevoSaldo - (int) $objeto->valor_total;
                            }

                            $objeto->saldo = $nuevoSaldo;

                            //Convertimos el objeto en un arreglo asociativo
                            $array_object = get_object_vars($objeto);
    
                            $movObjeto = new DeudaMovimiento($array_object);
                            $resultado = $movObjeto->actualizar();
                        }
                    }
                    
                    echo json_encode([
                        "rta" => "true",
                        "message" => "Registro actualizado"
                    ]);
                }else{
                    echo json_encode([
                        "rta" => "false",
                        "message" => "No fue posible actualizar el registro, error en el backend", 
                        "error" => $resultado
                    ]);
                }

            }else{
                echo json_encode([
                    "rta" => "false",
                    "message" => "No pasó la validación en los campos", 
                    "alertas" => $alertas
                ]);

            }

        }

    }

    public static function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            //Obtener el item actual y cargarlo en un objeto
            $get_mov = self::getMovimientoDeuda((int) $_POST['id']);

            //Obtener el saldo del item anterior, para obtenemos todo el objeto
            /** @var DeudaMovimiento $get_mov */
            $get_mov_prev = self::getMovimientoAnterior((int) $get_mov->getFkDeuda(), (int) $get_mov->getIdMov());
            
            //Obtenemos todos los demás items siguientes
            /**@var DeudaMovimiento $$movimiento  */
            $array_mov = self::getMovimientosUpdate($get_mov->getFkDeuda(), $get_mov->getIdMov());

            //sumar o restar según sea el caso en el item posterior
            if (!empty($array_mov)){

                /** @var DeudaMovimiento $get_mov_prev */                        
                $nuevoSaldo = (int) $get_mov_prev->getSaldo();

                foreach($array_mov as $objeto){

                    /** @var DeudaMovimiento $objeto */
                    if ($objeto->tipo_mov == "D"){
                        $nuevoSaldo = $nuevoSaldo + (int) $objeto->valor_total;
                    }else{
                        $nuevoSaldo = $nuevoSaldo - (int) $objeto->valor_total;
                    }
                    $objeto->saldo = $nuevoSaldo;
                    //Convertimos el objeto en un arreglo asociativo
                    $array_object = get_object_vars($objeto);

                    $movObjeto = new DeudaMovimiento($array_object);
                    $resultado = $movObjeto->actualizar();
                }
            }

            //eliminar el registro
            $resultado = $get_mov->eliminar((int) $_POST['id']);

            if($resultado === true){

                echo json_encode([
                    "rta" => "true", 
                    "message" => "Registro eliminado"
                ]);

            }else{

                echo json_encode([
                    "rta" => "false", 
                    "message" => "El registro No se eliminó, posiblemente este registro se encuentre relacionado con otra entidad",
                    "error" => $resultado
                ]);
            }
        }
    }

    public static function reporteCSV(Router $router){
        
        // Configuración de la base de datos
        $host = "localhost";
        $dbname = "dbdeudores";
        $user = "bfandino";
        $password = "Laura0405@";
        
        // Conectar a PostgreSQL
        $conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

        if (!$conn) {
            die("Error de conexión: " . pg_last_error());
        }

        // Definir la consulta SQL anidada
        $consulta_sql = "
                SELECT c.nombre, dm.saldo, dm.fecha
                FROM deuda_movimiento dm
                JOIN (
                    SELECT DISTINCT ON (fk_deuda) fk_deuda, id_mov
                    FROM deuda_movimiento
                    ORDER BY fk_deuda, id_mov DESC
                ) filtro ON dm.fk_deuda = filtro.fk_deuda AND dm.id_mov = filtro.id_mov
                JOIN deuda d ON dm.fk_deuda = d.id_deuda
                JOIN cliente c ON d.fk_cliente = c.id_cliente
                WHERE dm.saldo <> 0 
                AND c.nombre <> 'Usuario de prueba'
                ORDER BY d.fk_cliente ASC;
        ";

        // Nombre del archivo CSV a descargar
        $archivo = "reporte_general.csv";

        $router->renderIndex('reportes/reporte', [
            "conn" => $conn,
            "consulta_sql" => $consulta_sql,
            "archivo" => $archivo
        ]);
    }

    public static function reporteMovimientosDeudor(Router $router){
        /** @var ClienteAPI $cliente */
        $cliente = self::getCliente($_GET['id']);
        $movimientos_deudor = self::getTotalMovimientosDeudor($_GET['id']);
        
        $mpdf = new Mpdf([
            'margin_top' => 60, // Ajusta el margen superior según el tamaño del encabezado
            'margin_bottom' => 10 // También puedes ajustar el margen inferior si es necesario
        ]);
        $mpdf->SetAutoPageBreak(true, 25); // Ajusta el margen de la página

        $color_primario="#0538a5";
        $fuente="Arial"; 

        $stylesheet = "

            @page {
                font-family: $fuente;
                font-size:1rem;
            }

            .tabla{
                margin: 0 auto;
                border-collapse: collapse;
                border-spacing: 0;

            }
                
            .tabla-cliente{
                margin-top:0.5rem;
                border:1px solid $color_primario;
                border-radius:1rem;
                padding:0.01rem 0.01rem 1rem 0.01rem;
                text-align:center;
            }

            .titulo-h1 {
                width:100%;
                text-align:center;
                font-family:$fuente;
                padding:0;
                color:$color_primario;
                font-size: 1.8rem;
            }

            /*Header - Tabla del cliente*/
            .campo_cliente{
                width:5rem;
                color:$color_primario;
                font-weight:bold;
                font-size:0.8rem;
                vertical-align: top;
            }
            
            .dato_cliente{
                font-family:$fuente;
                font-size:0.8rem;
                vertical-align: top;
            }

            /*Body - Tabla de datos*/
            .titulo-h2 {
                font-family:$fuente;
                margin:0;
                color:$color_primario;
                font-size:1rem;
            }

            .th{
                font-size:0.8rem;
                font-family:$fuente;
                background-color:$color_primario;
                color:#fff;
            }

            .fecha{
                width:5rem;
                text-align:right;
                font-family:$fuente;
            }
                
            .hora{
                width:4rem; 
                height:1.5rem;
                font-family:$fuente;
                text-align:center;
            }

            .movimiento{
                width:6rem; 
                text-align:center; 
                height:1.5rem;
                font-family:$fuente;
            }

            .abono{
                background:#f0ff00;
            }

            .devolucion{
                background-color:#e57b86;
            }

            .descripcion{
                width:25rem; 
                height:1.5rem;
                font-family:$fuente;
            }

            .cant{
                width:3rem; 
                text-align:center; 
                height:1.5rem;
                font-family:$fuente;
            }

            .vunit{
                width:5rem;
                text-align:right;
                font-family:$fuente;
            }

            .vtotal{
                width:5rem;
                text-align:right;
                font-family:$fuente;
            }
            
            .size-tabla{
                font-size:0.8rem;
            }

            .titulo_total{
                text-align:center;
                font-family:$fuente;
                font-size:1rem;
                font-weight:bold;
                background-color:$color_primario;
                color:#fff;
            }

            .sumatotal{
                width:5rem;
                text-align:right;
                font-family:$fuente;
                font-weight:bold;
                font-size:1rem;
                background-color:$color_primario;
                color:#fff;
            }

            .nit{
                color:$color_primario;
                font-family:$fuente;
                font-weight:bold;
            }
             
            .footer{
                color:$color_primario;
                font-family:$fuente;
                font-weight:bold;
                font-size:0.9rem;
            }
        ";

        // Agregar los estilos al PDF
        $mpdf->WriteHTML("<style>" . $stylesheet . "</style>", \Mpdf\HTMLParserMode::HEADER_CSS);   
        
        $fechaActual = date("Y-m-d");
        // Definir el encabezado
        $mpdf->SetHTMLHeader('
            <table class="tabla" width="100%">
                <tr>
                    <td><img class="logo" src="/build/img/sistema/logo.png" width="90" /></td>
                    <td style="text-align:center;">
                        <h1 class="titulo-h1">&nbsp;Papelería y Miscelánea Sumercé</h1>
                        <p class="nit">Nit 23622049-3</p>
                    </td>
                    <td style="text-align:center;font-size:0.7rem;">
                        Página {PAGENO} de {nbpg}
                    </td>
                </tr>
            </table>

            <div class="tabla-cliente">
                <table style="margin-top:1rem;" class="tabla" width="95%">
                    <tr>
                        <td class="campo_cliente">Cliente:</td><td class="dato_cliente">' . $cliente->nombre .'</td>
                        <td class="campo_cliente">Cédula/Nit:</td><td class="dato_cliente">' . $cliente->cedula_nit .'</td>
                        <td class="campo_cliente">Fecha:</td><td class="dato_cliente">' . $fechaActual .'</td>
                    </tr>
                    <tr>
                        <td class="campo_cliente">Email:</td><td colspan="3" class="dato_cliente">' . $cliente->email .'</td>
                        <td class="campo_cliente">Teléfono:</td><td class="dato_cliente">' . $cliente->telefono .'</td>
                    </tr>
                    <tr>
                        <td class="campo_cliente">Dirección:</td><td class="dato_cliente">' . $cliente->direccion .'</td>
                        <td class="campo_cliente">Ciudad:</td><td class="dato_cliente">' . $cliente->nombre_ciudad .'</td>
                        <td class="campo_cliente">Departamento:</td><td class="dato_cliente">' . $cliente->nombre_depart .'</td>
                    </tr>
                </table>
            </div>
                
        ');

        $html="
                <table class='tabla-datos' style='width: 100%; border-collapse: collapse;'>
                <tr><td class='' colspan='7' style='text-align:center;'><h2 class='titulo-h2'>REPORTE DE MOVIMIENTOS</h2></td></tr>
                <tr>
                    <th class='th'>Fecha</th><th class='th'>Hora</th><th class='th'>Tipo Movimiento</th><th class='th'>Descripción</th><th class='th'>Cant.</th><th class='th'>V/Unit</th><th class='th'>V/Total</th>
                </tr>";

        if (isset($movimientos_deudor) && !empty($movimientos_deudor)){

            $saldo = 0;

            foreach($movimientos_deudor as $deudor){

                /** @var DeudaMovimiento $deudor */
                if ($deudor->tipo_mov == 'D'){
                    $html .="
                        <tr>
                            <td class='fecha size-tabla'>" . $deudor->fecha . "</td>
                            <td class='hora size-tabla'>" . $deudor->hora . "</td>
                            <td class='movimiento size-tabla'>Debe</td>
                            <td class='descripcion size-tabla'>" . $deudor->descripcion . "</td>
                            <td class='cant size-tabla'>" . $deudor->cant . "</td>
                            <td class='vunit size-tabla'>" . number_format($deudor->valor_unit, 0, ',', '.') . "</td>
                            <td class='vtotal size-tabla'>" . number_format($deudor->valor_total, 0, ',', '.') . "</td>
                        </tr>
                        ";

                }elseif($deudor->tipo_mov == 'A'){
                     $html .="
                        <tr>
                            <td class='fecha size-tabla abono'>" . $deudor->fecha . "</td>
                            <td class='hora size-tabla abono'>" . $deudor->hora . "</td>
                            <td class='movimiento size-tabla abono'>Abonó</td>
                            <td class='descripcion size-tabla abono'>" . $deudor->descripcion . "</td>
                            <td class='cant size-tabla abono'>" . $deudor->cant . "</td>
                            <td class='vunit size-tabla abono'>" . number_format($deudor->valor_unit, 0, ',', '.') . "</td>
                            <td class='vtotal size-tabla abono'>" . number_format($deudor->valor_total, 0, ',', '.') . "</td>
                        </tr>
                    ";

                }else{
                    $html .="
                        <tr>
                            <td class='fecha size-tabla devolucion'>" . $deudor->fecha . "</td>
                            <td class='hora size-tabla devolucion'>" . $deudor->hora . "</td>
                            <td class='movimiento size-tabla devolucion'>Devolución</td>
                            <td class='descripcion size-tabla devolucion'>" . $deudor->descripcion . "</td>
                            <td class='cant size-tabla devolucion'>" . $deudor->cant . "</td>
                            <td class='vunit size-tabla devolucion'>" . number_format($deudor->valor_unit, 0, ',', '.') . "</td>
                            <td class='vtotal size-tabla devolucion'>" . number_format($deudor->valor_total, 0, ',', '.') . "</td>
                        </tr>
                    ";
                }
                $saldo = $deudor->saldo;
            }
                    
            $html .= "<tr>";
            $html .= "<td colspan='6' class='titulo_total' style='background-color:$color_primario;'>TOTAL</td>";
            $html .= "<td class='sumatotal' style='background-color:$color_primario;'>" . number_format($saldo, 0, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</table>";

        }
      
        // $html .= "<h6 style='margin:0;font-size:1rem;'>Fecha Impresión: " . $fechaActual . "</h6>";
        
        // Definir el pie de página
        $mpdf->SetHTMLFooter('
            <hr>
            <table class="tabla" width="100%">
                <tr>
                    <td colspan="4" class="footer" style="text-align:center;padding-bottom:0.5rem;">Contáctenos</td>
                </tr>
                <tr>
                    <td class="footer"><img src="/build/img/sistema/phone.svg" width="0.8rem"/> 3123433699 </td>
                    <td class="footer" style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;<img src="/build/img/sistema/whatsapp.svg" width="0.8rem"/> 3135787739 </td>
                    <td class="footer" style="text-align:center;"><img src="/build/img/sistema/location.svg" width="0.65rem"/> Calle 12 # 6 - 03, Guateque</td>
                    <td class="footer" style="text-align:right;"><img src="/build/img/sistema/email.svg" width="0.8rem"/> pymsumerce@hotmail.com</td>
                </tr>
            </table>
        ');

        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        $nombre_archivo = "reporte " . $cliente->nombre . ".pdf";

        $router->renderIndex('reportes/reporte_pdf', [
            "mpdf" => $mpdf,
            "nombre_archivo" => $nombre_archivo
        ]);
    }

    public static function reporteDeudores(Router $router){
        
        $deudores = self::getDeudores();
        
        $mpdf = new Mpdf();
        $mpdf->SetAutoPageBreak(true, 10); // Ajusta el margen de la página
        $html="
                <table border='1' style='width: 23rem; border-collapse: collapse;'>
                <tr>
                    <th colspan='4' style='background-color:#e1e1e1'><h4>Deudores</h4></th>
                </tr>
                <tr>
                    <th style='font-size:0.8rem;background-color:#e1e1e1'>No.</th>
                    <th style='font-size:0.8rem;background-color:#e1e1e1'>Fecha</th>
                    <th style='font-size:0.8rem;background-color:#e1e1e1;'>Nombre del Cliente</th>
                    <th style='font-size:0.8rem;background-color:#e1e1e1;'>Saldo</th>
                </tr>";

        if (isset($deudores) && !empty($deudores)){
            
            $contador = 1;
            $suma_total = 0;
            
            foreach($deudores as $deudor){

                /** @var DeudoresReporte $deudor */
                $html .="
                    <tr>
                        <td style='width:2rem;text-align:center;font-family:Arial;font-size:0.8rem;'>" . $contador . "</td>
                        <td style='width:5rem;text-align:right;font-family:Arial;font-size:0.8rem;'>" . $deudor->fecha . "</td>
                        <td style='width:11rem; height:1.5rem;font-family:Arial;font-size:0.8rem;'>" . $deudor->nombre_cliente . "</td>
                        <td style='width:5rem;text-align:right;font-family:Arial;font-size:0.8rem;'>" . number_format($deudor->saldo, 0, ',', '.') . "</td>
                    </tr>
                ";

                $contador++;
                $suma_total += (int) $deudor->saldo;
            }

            $html .= "<tr>";
            $html .= "<td colspan='3' style='text-align:center;font-family:Arial;font-size:0.8rem;font-weight:bold;background-color:#e1e1e1;'>SUMA TOTAL</td>";
            $html .= "<td style='text-align:right;font-family:Arial;font-size:0.8rem;font-weight:bold;background-color:#e1e1e1;'>" . number_format($suma_total, 0, ',', '.') . "</td>";
            $html .= "</tr>";
            $html .= "</table>";

            $fechaActual = date("Y-m-d");
            $html .= "<h6 style='margin:0;font-size:1rem;'>Fecha Impresión: " . $fechaActual . "</h6>";
        }

        $mpdf->WriteHTML($html);
        $nombre_archivo = "deudores.pdf";

        $router->renderIndex('reportes/reporte_pdf', [
            "mpdf" => $mpdf,
            "nombre_archivo" => $nombre_archivo
        ]);
    }
}

?>