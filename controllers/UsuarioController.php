<?php

namespace Controllers;

use Model\API\Departamento;
use Model\Usuario;
use MVC\Router;

// use Model\Usuario;

class UsuarioController{

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
            $alertas = $usuario->validarCamposUsuario();

            // Validar campos
            if (empty($alertas)){

                $usuario_existCedula = $usuario->existeCedula($usuario->cedula_us); 
                $usuario_existNickname = $usuario->existeNickname($usuario->nickname_us);
                $usuario_existEmail = $usuario->existeEmail($usuario->email_us);

                // Verifica si alguno es un error (string)
                if (is_string($usuario_existCedula) || is_string($usuario_existNickname) || is_string($usuario_existEmail)) {
                    echo json_encode([
                        "rta" => "false",
                        "message" => "Error en la base de datos",
                        "error" => [
                            "cedula" => $usuario_existCedula,
                            "nickname" => $usuario_existNickname,
                            "email" => $usuario_existEmail
                        ]
                    ]);
                    return;
                }

                $usuario_exist = $usuario_existCedula || $usuario_existNickname || $usuario_existEmail;
                
                if (!$usuario_exist){

                    $resultado = $usuario->crear();
                    
                    if($resultado === true){
                        echo json_encode([
                            "rta" => "true", 
                            "message" => "Guadado exitosamente"
                        ]);
                    }else{
                        echo json_encode([
                            "rta" => "false", 
                            "message" => "Error de validación en la base de datos",
                            "error" => $resultado
                        ]);
                    }
                }else{
                    echo json_encode([
                        "rta" => "false", 
                        "message" => "La cédula, el nickname o el email ya existe en la base de datos"
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