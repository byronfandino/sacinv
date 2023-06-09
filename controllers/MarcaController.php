<?php

namespace Controllers;

use MVC\Router;
use Model\Marca;

class MarcaController{

    // API para mostrar el listado de categorias en formato JSON
    public static function listarMarcas (){    
        
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }
        $registros=Marca::all('Mc_Descripcion');
        echo json_encode($registros);
    }

    public static function guardarAPI(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $marca = new Marca();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //1. Primero sincronizamos los datos
            $marca->sincronizar($_POST);

            //2.Validamos si no hay errores en la digitación del usuario
            $alertas = $marca->validar();

            //3. Si hay alertas se termina la ejecución del código
            if(empty($alertas)){
                
                //4. Procedemos a verificar que no exista en la base de datos
                $existe = Marca::where('Mc_Descripcion', trim($marca->Mc_Descripcion));

                if(!$existe){

                    // Si el registro no existe en la base de datos se procede a guardar
                    $resultado = $marca->guardar(); 
                    echo json_encode($resultado);
                    
                }
            }
        }
        // $alertas=Categoria::getAlertas();
    }

    public static function index(Router $router){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $marca = new Marca();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //1. Primero sincronizamos los datos
            $marca->sincronizar($_POST);
            
            //2.Validamos si no hay errores en la digitación del usuario
            $alertas = $marca->validar();

            //3. Si hay alertas se termina la ejecución del código
            if(empty($alertas)){
                
                //4. Procedemos a verificar que no exista en la base de datos
                $existe = Marca::where('Mc_Descripcion', trim($marca->Mc_Descripcion));

                if($existe){
                    Marca::setAlerta('error-marca', 'nombre', 'Esta marca ya existe en la base de datos');
                }else{
                    
                    // Si el registro no existe en la base de datos se procede a guardar
                    $resultado = $marca->guardar(); 

                    if($resultado){
                        Marca::setAlerta('exito-marca', 'general', 'El registro ha sido guardado satisfactoriamente');
                    }else{
                        Marca::setAlerta('error-marca', 'general', 'No fue posible guardar el registro');
                    }
                }
            }
        }

        $alertas=Marca::getAlertas();

        $router->renderPanel('panel/marca/index', [
            'marca' => $marca,
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
        $marca = Marca::find($_GET['id']);

        $alertas=[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            // 1. Sincronizar lo que digito el usuario en el objeto en memoria
            $marca->sincronizar($_POST);

            // 2. Validamos lo digitado por el usuario
            $alertas = $marca->validar();

            if(empty($alertas)){
                
                // Si el nombre de la descripción no existe en la base de datos se procede a guardar
                $resultado = $marca->guardar();

                if($resultado){
                    Marca::setAlerta('exito-marca', 'general', 'El registro ha sido guardado satisfactoriamente');
                }else{
                    Marca::setAlerta('error-marca', 'general', 'No fue posible guardar el registro. Posiblemente ya exista un registro con la misma descripción o no hay conexión con la base de datos');
                }
            }
            $alertas=Marca::getAlertas();
        }

        //4. Verificamos si realiza algún cambio en el método POST
        $router->renderPanel('panel/marca/editar',[
            'marca' => $marca,
            'alertas' => $alertas
        ]);
    }

    public static function estado(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $marca = new Marca($_POST);
        $resultado = $marca->guardar();

        echo json_encode($resultado);
    }

    public static function eliminar(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $marca = new Marca($_POST);
        $resultado = $marca->eliminar($marca->Mc_Id);

        echo json_encode($resultado);
    }
}

?>