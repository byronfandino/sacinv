<?php

namespace Controllers;

use Model\Tabla;
use MVC\Router;

require_once '../includes/parameters.php';
header("Access-Control-Allow-Origin: " . $urlJSON); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");


class TablaController{
    
    public static function existeTabla ($nombre_tb){
        $query = "SELECT * FROM tabla WHERE nombre_tb ='" . $nombre_tb . "'";
        $result = Tabla::SQL($query);
        return isset($result[0]) ? $result[0] : null; //Retorna un arreglo y por lo tanto se indica la primera posición para que retorne el objeto
    }

    public static function inicio(Router $router){

        $router->renderIndex('/tabla/index', []);
    }

    public static function guardar(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $cliente = new Tabla($_POST);
            $alertas = $cliente->validar();

            if (empty($alertas)){

                $cliente_exist = self::existeClienteCedula($cliente->cedula_nit); 

                if (is_null($cliente_exist)){

                    $resultado = $cliente->crear();
                    
                    if($resultado === true){
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
                        "message" => "El cliente ya existe en la base de datos"
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

    // public static function actualizar(){
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //         $cliente = new Cliente($_POST);
            
    //         $alertas = $cliente->validar();
            
    //         if (empty($alertas)){
                
    //             $resultado = $cliente->actualizar();

    //             if($resultado === true){
    //                 echo json_encode([
    //                     "rta" => "true", 
    //                     "message" => "Actualización exitosa"
    //                 ]);
    //             }else{
    //                 echo json_encode([
    //                     "rta" => "false", 
    //                     "message" => "Error de validación en la base de datos"
    //                 ]);
    //             }
    //         }else{
                
    //             echo json_encode([
    //                 "rta" => "false",
    //                 "message" => "No cumple con la validación de campos", 
    //                 "alertas" => $alertas
    //             ]);
    //         }
    //     }
    // }

    // public static function eliminar(){

    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //         $id = $_POST['id'];
    //         $cliente = Cliente::find($id);
    //         $resultado = $cliente->eliminar($id);

    //         if($resultado === true){
    //             echo json_encode([
    //                 "rta" => "true", 
    //                 "message" => "Registro eliminado"
    //             ]);
    //         }else{
    //             echo json_encode([
    //                 "rta" => "false", 
    //                 "message" => "El registro No se eliminó, posiblemente este registro se encuentre relacionado con otra entidad",
    //                 "error" => $resultado
    //             ]);
    //         }
    //     }
    // }
}

?>