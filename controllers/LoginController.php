<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Classes\UsuarioMensaje;
use Model\Usuario;

class LoginController{
    
    public static function login(Router $router){
        
        $auth = new Usuario;
        // debuguear($auth);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth->sincronizar($_POST);
            $alertas = $auth->validarlogin();
            // Si los campos fueron diligenciados validamos en la base de datos 
          
            if (empty($alertas)){
                $usuario = Usuario::where('Us_NickName', $auth->Us_NickName);
                
                //Si existe el usuario
                if($usuario){
                    //Verificamos el password en la base de datos
                    if($usuario->comprobarPasswordDB($auth->Us_Password)){
                        //Si el password es correcto entonces creamos la sesión del usuario
                        session_start();

                        $_SESSION['id'] = $usuario->Us_Id;
                        $nombre= explode(' ', $usuario->Us_Nombre);
                        $_SESSION['nombre'] = $nombre[0];
                        $_SESSION['email'] = $usuario->Us_Email;
                        $_SESSION['login'] = true;
                        
                        if($usuario->Us_TipoUsuario == "A"){
                            $_SESSION['tipoUsuario'] = $usuario->Us_TipoUsuario;
                            header('Location: /panel-admin');
                        }elseif($usuario->Us_TipoUsuario == "U"){
                            $_SESSION['tipoUsuario'] = $usuario->Us_TipoUsuario;
                            header('Location: /panel-usuario');
                        }
                    }
                }else{
                    Usuario::setAlerta('error-log','nickname','Este usuario no se encuentra registrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->renderIndex('auth/login', [
            'auth' => $auth,
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        echo "Desde Logout";
    }

    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Creamos la instancia de la clase usuario
            $auth = new Usuario($_POST);

            //Verificamos que el usuario haya deligenciado el campo email
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                //Verificamos la existencia del email en la base de datos
                $usuario = Usuario::where('Us_Email', $auth->Us_Email);

                //Si existe el usuario y además se encuentra confirmado
                if ($usuario && $usuario->Us_Confirmado === '1'){
                    
                    //Se genera el token para enviar por correo
                    $usuario->crearToken();

                    //Se actualiza el token creado en la base de datos
                    $usuario->guardar();

                    //Enviamos el Email
                    $email = new Email($usuario->Us_Nombre, $usuario->Us_Email, $usuario->Us_Token);
        
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'general', 'Revisa tu email');
                    header('Location: /mensaje-recuperacion');

                }else{
                    $alertas = Usuario::setAlerta('error-olvide', 'email', 'Este email no se encuentra registrado');
                }

            }
            
        }

        $alertas = Usuario::getAlertas();

        $router->renderInicioMsg('auth/olvide', [
            'alertas' => $alertas
        ]);
    }

    public static function mensajeRecuperacion (Router $router){
        $alertas=[];
        
        $router->renderInicioMsg('auth/olvide', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
 
        $alertas = [];
        $usuario = new Usuario; 
        $error = false;

        $token = s($_GET['token']);
        $usuario = Usuario::where('Us_Token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error-general','general','Token No Válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            // debuguear($alertas);
            if (empty($alertas)){
                //Eliminamos el password del usuario asignandole null
                $usuario->Us_Password = null;
                $usuario->Us_Password = $password->Us_Password;
                $usuario->hashPassword();
                $usuario->Us_Token = 0;

                //Una vez encriptado el password y eliminado el token de la instancia del objeto, entonces actualizamos el usuario en la base de datos.
                $resultado = $usuario->guardar();
                if ($resultado){
                    Usuario::setAlerta('exito', 'general', 'Se ha cambiado la contraseña satisfactoriamente');
                    
                }
            }
        }
    
        $alertas = Usuario::getAlertas();
        $router->renderInicioMsg('auth/recuperar', [
            'alertas' => $alertas,
            'error' => $error,
            'usuario' => $usuario
        ]);
    }

    public static function crear(Router $router){

        $usuario = new Usuario();
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevoUsuario();

            //Revisamos que alertas esté vacío
            // Si el usuario diligenció ambos campos
            if (empty($alertas)){
                $resultado = $usuario->existeUsuario();    

                //Si el resultado es diferente a 0
                if ($resultado->num_rows){
                    //Si el usuario existe
                    $alertas = Usuario::getAlertas();
                }else{
                    //Si el usuario no existe
                    //Hassear el Password
                    $usuario->hashPassword();
                    
                    //Generar un Token único
                    $usuario->crearToken();
                    
                    //Creamos una instancia de Email
                    $email = new Email ($usuario->Us_Nombre, $usuario->Us_Email, $usuario->Us_Token);
                    
                    //Enviamos el email
                    $email->enviarConfirmacion();
                    
                    // Guardamnos el usuario 
                    $resultado = $usuario->guardar();


                    if ($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->renderIndex('auth/login', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $usuario = new Usuario;
        $alertas = [];

         $router->renderIndex('auth/login', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function confirmar(Router $router){
        
        $alertas=[];

        //Obteniendo el token de la URL
        $token = $_GET['token'] ?? '';

        // Verificar si existe el Token en la base de datos
        $usuario = Usuario::where('Us_Token', $token);
        
        if(empty($usuario)){
            //Si no existen registro en la base de datos
            Usuario::setAlerta('error-general', 'general', 'Error de comprobación de cuenta');
        }else{
            //En este punto el usuario si existe, por lo tanto cambiamos las propiedades del objeto para actualzar el registro en la base de datos
            $usuario->Us_Confirmado = "1";
            $usuario->Us_Token = "0";
            
            //Como ya existe un id, entonces se procede a actualizar el registro
            $resultado = $usuario->guardar();

            if ($resultado){
                //Si se actualizó exitosamente se guarda en el arreglo de alertas
                Usuario::setAlerta('exito', 'general','Cuenta comprobada correctamente');
            }else{
                // Si no fue posible actualizar el registro 
                Usuario::setAlerta('error-general', 'general', 'Error de activación de la cuenta');
            }
        }

        //Obtenemos las alertas para pasarlas a la Vista
        $alertas=Usuario::getAlertas();
        // debuguear($alertas);

        //Verificando la existencia del registro de acuerdo al token
        $router->renderInicioMsg('templates/notificacion', [
            'alertas' => $alertas
        ]);
    }

    public static function admin(){
        if(!isset($_SESSION['nombre'])){
            session_start();
        }
        
        isAdmin();

        echo "Desde Administrador";
    }

    public static function usuario(){
        echo "Desde Usuario";
    }

    public static function envioMensaje(Router $router){
        $usuarioMsg = new UsuarioMensaje();
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuarioMsg->sincronizar($_POST);

            $alertas = $usuarioMsg->validar();

            if(empty($alertas)){
                //Enviamos el correo
                if($usuarioMsg->enviarMensaje()){
                    UsuarioMensaje::setAlerta('exito', 'exito-general', 'El mensaje se ha enviado con éxito');
                }else{
                    UsuarioMensaje::setAlerta('error-general', 'general', 'No fue posible enviar el mensaje');
                }
                header('Location: /mensaje-enviado');
            }
        }

        $alertas = UsuarioMensaje::getAlertas();

        $router->renderIndex('auth/login', [
            'usuarioMsg' => $usuarioMsg,
            'alertas' => $alertas
        ]);
    }

    public static function mensajeEnviado(Router $router){
        $alertas = [];
        $usuarioMsg = new UsuarioMensaje;
        $router->renderIndex('auth/login', [
            'usuarioMsg' => $usuarioMsg,
            'alertas' => $alertas

        ]);
    }
}

?>