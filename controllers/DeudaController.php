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

class DeudaController{
    
    public static function inicio(Router $router){
        
        $departamentos = Departamento::all('nombre_depart');

        $router->renderIndex('deudores/index', [
            'departamentos' => $departamentos
        ]);
    }

    public static function getIdDeudaSaldo($deuda){

        //Obtenemos el objeto general de la deuda
        $query = "SELECT * FROM deuda WHERE fk_cliente = ". $deuda->fk_cliente . " ORDER BY fk_cliente DESC LIMIT 1";
        $arrayObjeto =  Deuda::SQL($query);

        $objeto = $arrayObjeto[0];
        $contador = 0;
        $arrayDatos = [];

        foreach ($objeto as $valor) {
            // Si es igual a 0 es por está en la primera posición 
            if ($contador == 0){
                $arrayDatos['id'] = $valor;
            }
            //Se sobreescribe el valor de la variable hasta llegar a la última propiedad que es el saldo
            $arrayDatos['saldo'] = $valor;

            $contador++;
        }
        return $arrayDatos;

    }

    public static function getClienteDeuda($fk_cliente){

        //Se obtiene el último registro del cliente que tiene saldo = 0
        $query = "SELECT * FROM deuda WHERE fk_cliente = " . $fk_cliente . " ORDER BY id_deuda DESC LIMIT 1";
        $clienteFound = Deuda::SQL($query);
        return $clienteFound;
    }

    public static function getMovimientosDeuda($fk_cliente){

    }

    public static function guardar(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $clienteFoundDeuda = self::getClienteDeuda((int) $_POST['fk_cliente']);
            
            if (!empty($clienteFoundDeuda)){
                //Si encontró el cliente es porque ya tiene un registro en la tabla deuda

                // En la variable $clienteFoundDeuda ya obtenemos el id_deuda para agregarlo al movimiento_deuda

                //Hay que verificar si ya tiene registros en deuda_movimiento de acuerdo al id_deuda obtenido, y en caso de no ser así, se crea un registro en deuda_movimiento con el saldo en 0, con el fin de tener un punto de partida para consultar el saldo, para eso se crea un objeto desde el backend

                //Luego se crea otro registro con los datos del post con el mismo fk_deuda en la tabla deuda_movimiento, el cual debe incluir el saldo






                echo json_encode([
                    "rta" => "false",
                    "message" => "Cliente encontrado", 
                    "error" => $clienteFoundDeuda
                ]);
            }else{

                //El cliente no existe en la tabla deuda y hay insertar un registro nuevo
                echo json_encode([
                    "rta" => "false",
                    "message" => "No eixste el cliente en la tabla deuda", 
                    "error" => $clienteFoundDeuda
                ]);
            }

            // $deuda = new Deuda($_POST);
            // $deudaMovimiento = new DeudaMovimiento($_POST);
            
            // $alertasDeuda = $deuda->validar();
            // $alertasMovimiento = $deudaMovimiento->validar();

            // if (empty($alertasDeuda) && empty($alertasMovimiento)){
                

            //     $resultado = $deuda->crear();

            //     // Si se guardó el deudor de manera exitosa
            //     if($resultado === true){

            //         $arrayDatos = self::getIdDeudaSaldo($deuda);
            //         $deudaMovimiento->setFkDeuda((int) $arrayDatos['id']);
            //         $resultado2 = $deudaMovimiento->crear();

            //         //Si se creó adecuadamente el nuevo registro de DeudaMovimiento entonces procedemos a actualizar el saldo de la tabla principal
            //         if ($resultado2 === true){

            //             $nuevoSaldo = 0;
            //             //Verificamos el valor del movimiento y el tipo de movimiento
            //             if ($deudaMovimiento->tipo_mov == "D"){
            //                 $nuevoSaldo = (int) $arrayDatos['saldo'] + (int) $deudaMovimiento->valor;
            //             }else{
            //                 $nuevoSaldo = (int) $arrayDatos['saldo'] - (int) $deudaMovimiento->valor;
            //             }

            //             //Se actualizan los campos principales de la deuda
            //             $deuda->setIdDeuda((int) $arrayDatos['id']);
            //             $deuda->setSaldo($nuevoSaldo);

            //             $resultado3 = $deuda->actualizar();

            //             if ($resultado3 === true){
            //                 echo json_encode([
            //                     "rta" => "true", 
            //                     "message" => "Registro creado exitosamente"
            //                 ]);
            //             }else{
            //                 echo json_encode([
            //                     "rta" => "false", 
            //                     "message" => "Se guardó el registro de la deuda y el movimiento de la deuda, pero no se actualizó el saldo",
            //                     "error" => $resultado
            //                 ]);
            //             }
            //         }else{
            //             echo json_encode([
            //                 "rta" => "false", 
            //                 "message" => "Se agregó el registro de la Deuda pero NO se guardó el movimiento de la Deuda",
            //                 "error" => $resultado
            //             ]);
            //         }

            //     }else{
            //         echo json_encode([
            //             "rta" => "false", 
            //             "message" => "El registro No se eliminó, posiblemente este registro se encuentre relacionado con otra entidad",
            //             "error" => $resultado
            //         ]);
            //     }
            // }else{
            //     $alertas = $alertasDeuda + $alertasMovimiento;

            //     echo json_encode([
            //         "rta" => "false",
            //         "message" => "No cumple con la validación de campos en el servidor", 
            //         "alertas" => $alertas
            //     ]);
            // }
        }

    }
    
}

?>