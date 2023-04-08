<?php

namespace Controllers;

use MVC\Router;
use Model\Categoria;

class CategoriaController{

    // API para mostrar el listado de categorias en formato JSON
    public static function listarCategorias (){
        
        $registros=Categoria::all('Ctg_Descripcion');
        echo json_encode($registros);
    }

    public static function index(Router $router){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $categoria = new Categoria();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //1. Primero sincronizamos los datos
            $categoria->sincronizar($_POST);

            //2.Validamos si no hay errores en la digitación del usuario
            $alertas = $categoria->validar();

            //3. Si hay alertas se termina la ejecución del código
            if(empty($alertas)){
                
                //4. Procedemos a verificar que no exista en la base de datos
                $existe = Categoria::where('Ctg_Descripcion', trim($categoria->Ctg_Descripcion));

                if($existe){

                    Categoria::setAlerta('error-categoria', 'nombre', 'Esta categoria ya existe en la base de datos');

                }else{
                    
                    // Si el registro no existe en la base de datos se procede a guardar
                    $resultado = $categoria->guardar(); 

                    if($resultado){
                        Categoria::setAlerta('exito-categoria', 'general', 'El registro ha sido guardado satisfactoriamente');

                    }else{
                        
                        Categoria::setAlerta('error-categoria', 'general', 'No fue posible guardar el registro');
                    }
                }
            }
        }

        $alertas=Categoria::getAlertas();

        $router->renderPanel('panel/categoria/index', [
            'categoria' => $categoria,
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
        
        //3. Buscar el id de la Categoria y cargar el objeto en memoria
        $categoria = Categoria::find($_GET['id']);

        $alertas=[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            // 1. Sincronizar lo que digito el usuario en el objeto en memoria
            $categoria->sincronizar($_POST);

            // 2. Validamos lo digitado por el usuario
            $alertas = $categoria->validar();

            if(empty($alertas)){
                
                // Si el nombre de la descripción no existe en la base de datos se procede a guardar
                $resultado = $categoria->guardar();

                if($resultado){
                    Categoria::setAlerta('exito-categoria', 'general', 'El registro ha sido guardado satisfactoriamente');
                }else{
                    Categoria::setAlerta('error-categoria', 'general', 'No fue posible guardar el registro. Posiblemente ya exista un registro con la misma descripción o no hay conexión con la base de datos');
                }
            }
            $alertas=Categoria::getAlertas();
        }

        //4. Verificamos si realiza algún cambio en el método POST
        $router->renderPanel('panel/categoria/editar',[
            'categoria' => $categoria,
            'alertas' => $alertas
        ]);
    }

    public static function estado(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $categoria = new Categoria($_POST);
        $resultado = $categoria->guardar();

        echo json_encode($resultado);
    }

    public static function eliminar(Router $router ){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }
        
        $categoria = new Categoria($_POST);
        $resultado = $categoria->eliminar($categoria->Ctg_Id);
        echo json_encode($resultado);
    }
}

?>