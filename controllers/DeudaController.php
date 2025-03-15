<?php

namespace Controllers;

use Mpdf\Mpdf;
use Model\Cliente;
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

    public static function getClienteDeuda($fk_cliente){

        $query = "SELECT * FROM deuda WHERE fk_cliente = " . $fk_cliente . " ORDER BY id_deuda DESC LIMIT 1";
        $clienteFound = Deuda::SQL($query);
        return isset($clienteFound[0]) ? $clienteFound[0] : null; //Retorna un arreglo y por lo tanto se indica la primera posición para que retorne el objeto
    }

    public static function getUltimoMovimiento($fk_deuda){
        $query = "SELECT * FROM deuda_movimiento WHERE fk_deuda = " . $fk_deuda . " ORDER BY id_mov DESC LIMIT 1";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return isset($mov_deuda[0]) ? $mov_deuda[0] : null; //Retornamos únicamente el objeto
    }

    public static function getMovimientoDeuda($id_mov){
        $query = "SELECT * FROM deuda_movimiento WHERE id_mov = " . $id_mov . " LIMIT 1";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return isset($mov_deuda[0]) ? $mov_deuda[0] :  null; //Retornamos únicamente el objeto
    }

    //Obtiene todos los registros continuos al id buscado
    public static function getMovimientosUpdate($fk_deuda, $id_mov){
        $query = "SELECT * FROM deuda_movimiento WHERE fk_deuda = " . $fk_deuda . " AND id_mov > " . $id_mov . " ORDER BY id_mov ASC";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return $mov_deuda; //Retornamos el arreglo
    }

    public static function getMovimientoAnterior($fk_deuda, $id_mov){
        $query = "SELECT * FROM deuda_movimiento WHERE id_mov < " . $id_mov . " AND fk_deuda = " . $fk_deuda . " ORDER BY id_mov DESC LIMIT 1";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return isset($mov_deuda[0]) ? $mov_deuda[0] : null; //Retornamos únicamente el objeto        
    }

    public static function getDeudores(){
        $query = "SELECT c.nombre AS nombre_cliente, dm.saldo, dm.fecha FROM deuda_movimiento dm JOIN ( SELECT DISTINCT ON (deuda_movimiento.fk_deuda) deuda_movimiento.fk_deuda, deuda_movimiento.id_mov FROM deuda_movimiento ORDER BY deuda_movimiento.fk_deuda, deuda_movimiento.id_mov DESC) filtro ON dm.fk_deuda = filtro.fk_deuda AND dm.id_mov = filtro.id_mov JOIN deuda d ON dm.fk_deuda = d.id_deuda JOIN cliente c ON d.fk_cliente = c.id_cliente WHERE dm.saldo <> 0::double precision AND c.nombre::text <> 'Usuario de prueba'::text ORDER BY c.nombre";
        $mov_deuda = DeudoresReporte::SQL($query);
        return $mov_deuda; //Se retorna el arreglo
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

        $router->renderIndex('deudores/reporte_general', [
            "conn" => $conn,
            "consulta_sql" => $consulta_sql,
            "archivo" => $archivo
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
            $html .= "<h6 style='margin:0;'>Fecha Impresión: " . $fechaActual . "</h6>";
        }


        $mpdf->WriteHTML($html);

        $router->renderIndex('deudores/reporte_deudores', [
            "mpdf" => $mpdf
        ]);
    }
}

?>