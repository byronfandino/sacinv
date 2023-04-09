<?php

namespace Controllers;

use MVC\Router;
use Model\Tabla;

class TablaController{

    public static function listarTablas(){
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $registros = Tabla::all('Tb_Nombre');
        echo json_encode($registros);
    }

    public static function index(Router $router){
        
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $tabla = new Tabla();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //1. Primero sincronizamos los datos
            $tabla->sincronizar($_POST);
            
            //2.Validamos si no hay errores en la digitación del usuario
            $alertas = $tabla->validar();

            //3. Si hay alertas se termina la ejecución del código
            if(empty($alertas)){
                
                //4. Procedemos a verificar que no exista en la base de datos
                $existe = Tabla::where('Tb_Nombre', trim($tabla->Tb_Nombre));

                if($existe){
                    Tabla::setAlerta('error-tabla', 'nombre', 'Esta tabla ya existe en la base de datos');
                }else{
                    
                    // Si el registro no existe en la base de datos se procede a guardar
                    $resultado = $tabla->guardar(); 

                    if($resultado){
                        Tabla::setAlerta('exito-tabla', 'general', 'El registro ha sido guardado satisfactoriamente');
                    }else{
                        Tabla::setAlerta('error-tabla', 'general', 'No fue posible guardar el registro');
                    }
                }
            }
        }

        $alertas=Tabla::getAlertas();

        $router->renderPanel('panel/tabla/index', [
            'tabla' => $tabla,
            'alertas' => $alertas
        ]);
    }


    public static function editar(Router $router){

        // 1. Verificar que el usuario haya iniciado sesión y que por el método get haya recibido 
        if(!isset($_SESSION['nombre']) || ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id']))){
            header('Location: /');
        }

        //2. Verificar si hay un elemento get y es un número
        if(!is_numeric($_GET['id'])) return;
        
        //3. Buscar el id de la Marca y cargar el objeto en memoria
        $tabla = Tabla::find($_GET['id']);

        $alertas=[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            // 1. Sincronizar lo que digito el usuario en el objeto en memoria
            $tabla->sincronizar($_POST);

            // 2. Validamos lo digitado por el usuario
            $alertas = $tabla->validar();

            if(empty($alertas)){
                
                // Si el nombre de la descripción no existe en la base de datos se procede a guardar
                $resultado = $tabla->guardar();

                if($resultado){
                    Tabla::setAlerta('exito-tabla', 'general', 'El registro ha sido guardado satisfactoriamente');
                }else{
                    Tabla::setAlerta('error-tabla', 'general', 'No fue posible guardar el registro. Posiblemente ya exista un registro con la misma descripción o no hay conexión con la base de datos');
                }
            }
            $alertas=Tabla::getAlertas();
        }

        //4. Verificamos si realiza algún cambio en el método POST
        $router->renderPanel('panel/tabla/editar',[
            'tabla' => $tabla,
            'alertas' => $alertas
        ]);
    }

        
    public static function estado(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $tabla = new Tabla($_POST);
        $resultado = $tabla->guardar();

        echo json_encode($resultado);
    }

    public static function eliminar(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $tabla = new Tabla($_POST);
        $resultado = $tabla->eliminar($tabla->Tb_Id);

        echo json_encode($resultado);
    }
}

?>