<?php

namespace Controllers;

use Model\ProductoImgVideo;

class ProductoImgVideoController {
    
    // Esta función solo se utllizara para mostrar en tablas los items a mostrar
    public static function listarArchivos(){
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $id = $_GET['id'];

        //2. Verificar si hay un elemento get y es un número
        if(!is_numeric($id)) return;

        $consultaImgVideos = "SELECT ImVd_Id, ImVd_Nombre FROM tblproducto_img_video WHERE ImVd_FkProd_Id = " . $id;
        $resultadoImgVideos = ProductoImgVideo::SQL($consultaImgVideos);

        echo json_encode($resultadoImgVideos);

    }

    // public static function listarImgVideo(){
        
    //     if(!isset($_SESSION['nombre'])){
    //         header('Location: /');
    //     }

    //     if(!is_numeric($_GET['id'])) return;

    //     $id = $_GET['id'];
        
    //     $consulta = "SELECT * FROM tblproducto_img_video WHERE ImVd_Id = " . $id;

    //     $registros=ProductoImgVideo::SQL($consulta);
      
    //     echo json_encode($registros);
    // }

    public static function eliminar(){

        // Primero verificar en la base de datos que exista el archivo mediante el id
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }
        
        $id=$_POST['id'];
        $imgVideo = ProductoImgVideo::find($id);
        $resultado = $imgVideo->eliminar($imgVideo->ImVd_Id);
        
        //Eliminamos el archivo del servidor
        if($resultado){
            $existe = file_exists(CARPETA_PRODUCTOS . $imgVideo->ImVd_Nombre);
            // Si existe el archivo se elimina del servidor
            if ($existe){

                //1. Eliminamos el archivo original
                unlink(CARPETA_PRODUCTOS . $imgVideo->ImVd_Nombre);
                
                //2. Luego eliminamos el archivo optimizado en caso que exista (porque la excepción son los videos)
                $dividirArchivo = explode(".", $imgVideo->ImVd_Nombre);
                $existe = file_exists(CARPETA_PRODUCTOS . $dividirArchivo[0] . "-opt." . $dividirArchivo[1]);

                if ($existe){
                    unlink(CARPETA_PRODUCTOS . $dividirArchivo[0] . "-opt." . $dividirArchivo[1]);
                }
            }
        }
        
        echo json_encode($resultado);
    }
}
?>