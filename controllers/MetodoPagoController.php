<?php

namespace Controllers;

use MVC\Router;
use Model\MetodoPago;

class MetodoPagoController{

    public static function listarMetodosPagos(){
                
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }
        $registros=MetodoPago::all('MP_Nombre');
        echo json_encode($registros);
    }

    public static function index(Router $router){
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $metodoPago = new MetodoPago();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //1. Primero sincronizamos los datos
            $metodoPago->sincronizar($_POST);
            //2.Validamos si no hay errores en la digitación del usuario
            $alertas = $metodoPago->validar();

            //3. Si hay alertas se termina la ejecución del código
            if(empty($alertas)){
                
                //4. Procedemos a verificar que no exista en la base de datos
                $existe = MetodoPago::where('MP_Nombre', trim($metodoPago->MP_Nombre));
                if($existe){
                    MetodoPago::setAlerta('error-metodoPago', 'nombre', 'Este método de pago ya existe en la base de datos');
                    // debuguear($existe);
                }else{
                    
                    // Si el registro no existe en la base de datos se procede a guardar
                    $resultado = $metodoPago->guardar(); 
                    // debuguear($resultado);
                    if($resultado){
                        $metodoPago = new MetodoPago();
                        MetodoPago::setAlerta('exito-metodoPago', 'general', 'El registro ha sido guardado satisfactoriamente');
                        // Limpiamos el objeto asignando un nuevo objeto en blanco
                    }else{
                        MetodoPago::setAlerta('error-metodoPago', 'general', 'No fue posible guardar el registro');
                    }
                }
            }
        }

        $alertas=MetodoPago::getAlertas();

        $router->renderPanel('panel/metodopago/index', [
            'metodoPago' => $metodoPago,
            'alertas' => $alertas
        ]);
    }

    public static function guardarAPI(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $metodoPago = new MetodoPago();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //1. Primero sincronizamos los datos
            $metodoPago->sincronizar($_POST);

            //2.Validamos si no hay errores en la digitación del usuario
            $alertas = $metodoPago->validar();

            //3. Si hay alertas se termina la ejecución del código
            if(empty($alertas)){
                
                //4. Procedemos a verificar que no exista en la base de datos
                $existe = MetodoPago::where('MP_Nombre', trim($metodoPago->MP_Nombre));

                if(!$existe){

                    // Si el registro no existe en la base de datos se procede a guardar
                    $resultado = $metodoPago->guardar(); 
                    echo json_encode($resultado);
                    
                }
            }
        }
    }


    public static function editar(Router $router){

        // 1. Verificar que el usuario haya iniciado sesión y que por el método get haya recibido 
        if(!isset($_SESSION['nombre']) || ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id']))){
            header('Location: /');
        }

        //2. Verificar si hay un elemento get y es un número
        if(!is_numeric($_GET['id'])) return;
        
        //3. Buscar el id de la Marca y cargar el objeto en memoria
        $metodoPago = MetodoPago::find($_GET['id']);

        $alertas=[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            // 1. Sincronizar lo que digito el usuario en el objeto en memoria
            $metodoPago->sincronizar($_POST);

            // 2. Validamos lo digitado por el usuario
            $alertas = $metodoPago->validar();

            if(empty($alertas)){
                
                // Si el nombre de la descripción no existe en la base de datos se procede a guardar
                $resultado = $metodoPago->guardar();

                if($resultado){
                    MetodoPago::setAlerta('exito-metodoPago', 'general', 'El registro ha sido guardado satisfactoriamente');

                }else{
                    MetodoPago::setAlerta('error-metodoPago', 'general', 'No fue posible guardar el registro. Posiblemente ya exista un registro con la misma descripción o no hay conexión con la base de datos');
                }
            }
            $alertas=MetodoPago::getAlertas();
        }

        //4. Verificamos si realiza algún cambio en el método POST
        $router->renderPanel('panel/metodopago/editar',[
            'metodoPago' => $metodoPago,
            'alertas' => $alertas
        ]);
    }

    
    public static function estado(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $metodoPago = new MetodoPago($_POST);
        $resultado = $metodoPago->guardar();

        echo json_encode($resultado);
    }

    public static function eliminar(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $metodoPago = new MetodoPago($_POST);
        $resultado = $metodoPago->eliminar($metodoPago->MP_Id);

        echo json_encode($resultado);
    }
}

?>