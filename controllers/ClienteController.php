<?php

namespace Controllers;
use MVC\Router;
use Model\Cliente;
use Model\Departamento;

require_once '../includes/parameters.php';
header("Access-Control-Allow-Origin: " . $urlJSON); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class ClienteController{
    public static function inicio(Router $router){
        $departamentos = Departamento::all('nombre_depart');

        $router->renderIndex('/cliente/index', [
            'departamentos' => $departamentos
        ]);
    }

    public static function guardar(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $cliente = new Cliente($_POST);
            
            $alertas = $cliente->validar();

            if (empty($alertas)){
                $resultado = $cliente->crear();
                
                if($resultado){
                    echo json_encode([
                        "rta" => "true", 
                        "message" => "Guadado exitosamente"
                    ]);
                }else{
                    echo json_encode([
                        "rta" => "false", 
                        "message" => "Error de validación en la base de datos"
                    ]);
                }
            }else{
                
                echo json_encode([
                    "rta" => "false",
                    "message" => "No cumple con la validación de campos", 
                    "alertas" => $alertas
                ]);
            }

        }
        
    }

    public static function actualizar(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $cliente = new Cliente($_POST);
            
            $alertas = $cliente->validar();
            
            if (empty($alertas)){
                
                $resultado = $cliente->actualizar();

                if($resultado){
                    echo json_encode([
                        "rta" => "true", 
                        "message" => "Actualización exitosa"
                    ]);
                }else{
                    echo json_encode([
                        "rta" => "false", 
                        "message" => "Error de validación en la base de datos"
                    ]);
                }
            }else{
                
                echo json_encode([
                    "rta" => "false",
                    "message" => "No cumple con la validación de campos", 
                    "alertas" => $alertas
                ]);
            }
        }
    }

    public static function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $_POST['id_cliente'];
            $cliente = Cliente::find($id);
            $resultado = $cliente->eliminar($id);

            if($resultado){
                echo json_encode([
                    "rta" => "true", 
                    "message" => "Registro eliminado"
                ]);
            }else{
                echo json_encode([
                    "rta" => "false", 
                    "message" => "EL registro no pùdo ser eliminado"
                ]);
            }
        }
    }
}

?>