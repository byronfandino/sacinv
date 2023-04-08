<?php

namespace Controllers;

use Model\Parametro;
use MVC\Router;
use Intervention\Image\ImageManagerStatic as Image;

class ParametroController{

    public static function index(Router $router){
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $alertas=[];
        $parametro = Parametro::find(1);
        
        if(!$parametro){
            $parametro = new Parametro();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            //1. Primero sincronizamos los datos
            $parametro->sincronizar($_POST);
            
            /*-- SUBIDAS DE ARCHIVOS --*/
            //Generar un nombre de la imagen único
            $nombreImagen="";
            $image="";

            //Verificamos si se adjunto algún archivo
            if(isset($_FILES['Pm_Logo']['tmp_name']) && $_FILES['Pm_Logo']['tmp_name'] !== ""){
                
                // Verificamos la imagen subida al servidor
                $validarImagen = $parametro->verificarImagen($_FILES['Pm_Logo']);

                if(empty($validarImagen)){
    
                    // Verificamos si existe la carpeta donde se guardara el logo
                    if(!is_dir(CARPETA_LOGO)){
                        mkdir(CARPETA_LOGO);
                    }
                    
                    $nombreImagen=md5( uniqid( rand(), true ) ) . ".png";
    
                    //Realiza un rezise a la imagen con intervention
                    $image = Image::make($_FILES['Pm_Logo']['tmp_name'])->resize(null, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $parametro->setImagen($nombreImagen);
                }
            }else{
                // si el usuario no seleccionó ningún archivo entonces se verifica si ya existía una imagen en la base de datos
                $existeImagen = Parametro::find(1);   

                // Se realiza esta validación debido a que puede haber errores cuando se crea un nuevo registro
                if (isset($existeImagen->Pm_Logo)){
                    $nombreImagen = $existeImagen->Pm_Logo;
                    $parametro->setImagen($nombreImagen);
                }            
            }

            //2.Validamos si no hay errores en la digitación del usuario
            $alertas = $parametro->validar();
            
            //3. Si hay alertas se termina la ejecución del código
            if(empty($alertas)){
                
                //4. Procedemos a verificar que no exista en la base de datos
                $registroDB = Parametro::where('Pm_Id', 1);

                if($registroDB){
                    
                    // Subir la imagen al servidor
                    if($_FILES['Pm_Logo']['tmp_name']){
                        // Verificamos si existe un nombre de imagen guardado en la base de datos para eliminarla de forma física en el servidor
                        if($registroDB->Pm_Logo != ""){
                            unlink(CARPETA_LOGO . $registroDB->Pm_Logo);
                        }

                        $image->save(CARPETA_LOGO . $nombreImagen);
                    }

                    $resultado = $parametro->guardar(); 
                    
                    if($resultado){
                        Parametro::setAlerta('exito-parametro', 'general', 'El registro ha sido guardado satisfactoriamente');
                    }else{
                        Parametro::setAlerta('error-parametro', 'general', 'No fue posible guardar el registro');
                    }
                }else{
                    // Si el registro no existe en la base de datos se procede a guardar
                    
                    // Subir la imagen al servidor
                    if($_FILES['Pm_Logo']['tmp_name']){
                        $image->save(CARPETA_LOGO . $nombreImagen);
                    }
                    
                    $resultado = $parametro->guardar(); 
                    
                    if($resultado){
                        Parametro::setAlerta('exito-parametro', 'general', 'El registro ha sido guardado satisfactoriamente');
                    }else{
                        Parametro::setAlerta('error-parametro', 'general', 'No fue posible guardar el registro');
                    }
                }
            }
        }

        $alertas=Parametro::getAlertas();

        $router->renderPanel('panel/parametro/index',[
            'parametro' => $parametro,
            'alertas' => $alertas
        ]);
    }

    public static function eliminarLogo(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $resultado = false;

        if($_SERVER['REQUEST_METHOD'] === "POST"){
            
            $parametro = Parametro::find(1);

            if($parametro){
                // eliminamos el logo del servidor
                unlink(CARPETA_LOGO . $parametro->Pm_Logo);
                // Renombramos el valor del objeto, para guardarlo en la base de datos
                $parametro->Pm_Logo = null;
                $resultado = $parametro->guardar();
            }
        }

        echo json_encode(['resultado' => $resultado]);
    }
}

?>