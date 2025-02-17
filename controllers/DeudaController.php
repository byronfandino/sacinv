<?php

namespace Controllers;

use Model\Cliente;
use MVC\Router;
use Model\Departamento;
use Model\Deuda;
use Model\DeudaMovimiento;

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

        //Se obtiene el último registro del cliente que tiene saldo = 0
        $query = "SELECT * FROM deuda WHERE fk_cliente = " . $fk_cliente . " ORDER BY id_deuda DESC LIMIT 1";
        $clienteFound = Deuda::SQL($query);
        return $clienteFound[0]; //Retorna un arreglo y por lo tanto se indica la primera posición para que retorne el objeto
    }

    public static function getUltimoMovimiento($fk_deuda){
        //Se obtiene el último registro del cliente que tiene saldo = 0
        $query = "SELECT * FROM deuda_movimiento WHERE fk_deuda = " . $fk_deuda . " ORDER BY id_mov DESC LIMIT 1";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return $mov_deuda[0]; //Retornamos únicamente el objeto
    }

    public static function getMovimientoDeuda($id_mov){
        //Se obtiene el último registro del cliente que tiene saldo = 0
        $query = "SELECT * FROM deuda_movimiento WHERE id_mov = " . $id_mov . " LIMIT 1";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return $mov_deuda[0]; //Retornamos únicamente el objeto
    }

    public static function getMovimientosUpdate($fk_deuda, $id_mov){
        //Se obtiene el último registro del cliente que tiene saldo = 0
        $query = "SELECT * FROM deuda_movimiento WHERE fk_deuda = " . $fk_deuda . " AND id_mov > " . $id_mov . " ORDER BY id_mov ASC";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return $mov_deuda; //Retornamos el arreglo
    }

    public static function getMovimientoAnterior($fk_deuda, $id_mov){
        //Se obtiene el último registro del cliente que tiene saldo = 0
        $query = "SELECT * FROM deuda_movimiento WHERE id_mov < " . $id_mov . " AND fk_deuda = " . $fk_deuda . " ORDER BY id_mov DESC LIMIT 1";
        $mov_deuda = DeudaMovimiento::SQL($query);
        return $mov_deuda[0]; //Retornamos únicamente el objeto        
    }

    public static function guardar(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $clienteInDeuda = self::getClienteDeuda((int) $_POST['fk_cliente']);
            
            if (empty($clienteInDeuda)){
                //La deuda no existe y hay que crearla
                $deuda = new Deuda($_POST);
                $alertas = $deuda->validar();

                if(empty($alertas)){
                    
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
                            "valor" => 0,
                            "fecha" => $fecha_actual,
                            "hora" => $hora_actual,
                            "saldo" => 0
                        ];

                        $deudaMovimiento = new DeudaMovimiento($newObjectMov);
                        $resultado = $deudaMovimiento->crear();

                        if($resultado == true){

                            //Agregamos la llave foránea al POST
                            $_POST['fk_deuda'] = $fk_deuda;
                            $_POST['saldo'] = (int) $_POST['valor'];

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
                                        "error" => [$_POST, $resultado]
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
                    echo json_encode([
                        "rta" => "false",
                        "message" => "La deuda no existe pero no pasó la validación de campos", 
                        "alertas" => $alertas
                    ]);
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
                            "valor" => 0,
                            "fecha" => $fecha_actual,
                            "hora" => $hora_actual,
                            "saldo" => 0
                        ];

                        $deudaMovimiento = new DeudaMovimiento($newObjectMov);
                        $resultado = $deudaMovimiento->crear();

                        if($resultado == true){

                            //Agregamos la llave foránea al POST
                            $_POST['fk_deuda'] = $fk_deuda;
                            $_POST['saldo'] = (int) $_POST['valor'];

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
                    //Se guarda el movimiento 
                    //Actualizamos el arreglo POST
                    $_POST['fk_deuda'] = $fk_deuda;
                    
                    //Se verifica el tipo de movimiento
                    if ($_POST['tipo_mov'] == "D"){
                        $_POST['saldo'] = (int) $lastSaldo + (int) $_POST['valor'];
                    }else{
                        $_POST['saldo']= (int) $lastSaldo - (int) $_POST['valor'];
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


            $get_mov = self::getMovimientoDeuda((int) $_POST['id_mov']);

            /** @var DeudaMovimiento $get_mov */
            $_POST['fk_deuda'] = (int) $get_mov->getFkDeuda();
            $_POST['fecha'] = date("Y-m-d"); // Obtiene la fecha actual 
            $_POST['hora'] = date("H:i"); // Obtiene la hora actual

            if ($_POST['tipo_mov'] == "D"){
                
                $_POST['saldo'] = (int) $get_mov->getSaldo() - (int) $get_mov->getValor();
                $_POST['saldo'] = (int) $_POST['saldo'] + (int) $_POST['valor'];
            
            }else{

                // Consultamos el saldo del registro anterior
                $get_mov_ant = self::getMovimientoAnterior((int) $get_mov->getFkDeuda(), (int) $_POST['id_mov']);
                /** @var DeudaMovimiento $get_mov_ant */
                $_POST['saldo'] = (int) $get_mov_ant->getSaldo() - (int) $_POST['valor'];
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

                        // $array_result = [];
                        // $array_result[0] = $nuevoSaldo; 
                        // $cont = 1;
    
                        foreach($array_mov as $objeto){
    
                            /** @var DeudaMovimiento $objeto */
                            if ($objeto->tipo_mov == "D"){
                                // $array_result[$cont] = $nuevoSaldo . " + " . (int) $objeto->valor;
                                $nuevoSaldo = $nuevoSaldo + (int) $objeto->valor;
                            }else{
                                // $array_result[$cont] = $nuevoSaldo . " - " . (int) $objeto->valor;
                                $nuevoSaldo = $nuevoSaldo - (int) $objeto->valor;
                            }

                            // $cont++;

                            $objeto->saldo = $nuevoSaldo;
                            //Convertimos el objeto en un arreglo asociativo
                            $array_object = get_object_vars($objeto);
    
                            $movObjeto = new DeudaMovimiento($array_object);
                            $resultado = $movObjeto->actualizar();
                        }
                    }
                    
                    echo json_encode([
                        "rta" => "true",
                        "message" => "Registro actualizado, mostrando los datos a actualizar"
                    ]);
                }else{
                    echo json_encode([
                        "rta" => "false",
                        "message" => "No fue posible actualizar el registro, error en backend", 
                        "error" => $resultado
                    ]);
                }

            }else{
                echo json_encode([
                    "rta" => "false",
                    "message" => "No pasó la validación en los campos", 
                    "error" => $alertas
                ]);

            }

        }

    }
}

?>