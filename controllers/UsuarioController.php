<?php

namespace Controllers;

use Model\API\Departamento;
use Model\Usuario;
use MVC\Router;

// use Model\Usuario;

class UsuarioController{

    public static function existeCedula ($cedula_us){
        $query = "SELECT * FROM usuario_sistema WHERE cedula_us ='" . $cedula_us . "'";
        $result = Usuario::SQL($query);
        return isset($result[0]) ? $result[0] : null; //Retorna un arreglo y por lo tanto se indica la primera posición para que retorne el objeto
    }

    public static function inicio(Router $router){
        // session_start();
        // if(!isset($_SESSION['nombre'])){
        //     header('Location: /');
        // }

        // listar departamentos      
        $departamentos = Departamento::all('nombre_depart');

        $router->renderIndex('/usuario/index', [
            'departamentos' => $departamentos
        ]);

    }

    public static function guardar(){
        // session_start();
        // if(!isset($_SESSION['nombre'])){
        //     header('Location: /');
        // }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validar();

            if (empty($alertas)){

                $usuario_exist = self::existeCedula($usuario->cedula_us); 

                if (is_null($usuario_exist)){

                    $resultado = $usuario->crear();
                    
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

    //Api de nicknames y correos electronicos
    // public static function listarNicknames(){
        
    //     $usuarios=[];
    //     $emails=[];

    //     //obtenemos todos los usuarios del sistema para acceder a los nickname e emails de cada uno 
    //     $usuarios_sistema=Usuario::all('Us_NickName');
        
    //     //Creamos dos arreglos, uno de emails y otro de NickNames, para evitar pasar información innecesaria
    //     foreach($usuarios_sistema as $usuario){
    //         $usuarios[] = $usuario->Us_NickName;
    //         $emails[] = $usuario->Us_Email;
    //     }
    //     echo json_encode([$usuarios, $emails]);
    // }
}

?>