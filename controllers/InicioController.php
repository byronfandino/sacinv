<?php

namespace Controllers;

use MVC\Router;
use Model\Departamento;

class InicioController{
    
    public static function inicio(Router $router){
        
        $departamentos = Departamento::all('nombre_depart');

        $router->renderIndex('deudores/index', [
            'departamentos' => $departamentos
        ]);
    }
    
        // $auth = new Usuario;
        // // debuguear($auth);
        // $alertas = [];

        // if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //     $auth->sincronizar($_POST);
        //     $alertas = $auth->validarlogin();
        //     // Si los campos fueron diligenciados validamos en la base de datos 
          
        //     if (empty($alertas)){
        //         $usuario = Usuario::where('Us_NickName', $auth->Us_NickName);
                
        //         //Si existe el usuario
        //         if($usuario){
        //             //Verificamos el password en la base de datos
        //             if($usuario->comprobarPasswordDB($auth->Us_Password)){
        //                 //Si el password es correcto entonces creamos la sesión del usuario
        //                 session_start();

        //                 $_SESSION['id'] = $usuario->Us_Id;
        //                 $nombre= explode(' ', $usuario->Us_Nombre);
        //                 $_SESSION['nombre'] = $nombre[0];
        //                 $_SESSION['email'] = $usuario->Us_Email;
        //                 $_SESSION['login'] = true;
                        
        //                 if($usuario->Us_TipoUsuario == "A"){
        //                     $_SESSION['tipoUsuario'] = $usuario->Us_TipoUsuario;
        //                     header('Location: /panel-admin');
        //                 }elseif($usuario->Us_TipoUsuario == "U"){
        //                     $_SESSION['tipoUsuario'] = $usuario->Us_TipoUsuario;
        //                     header('Location: /panel-usuario');
        //                 }
        //             }
        //         }else{
        //             Usuario::setAlerta('error-log','nickname','Este usuario no se encuentra registrado');
        //         }
        //     }
        // }
        // $alertas = Usuario::getAlertas();
}

?>