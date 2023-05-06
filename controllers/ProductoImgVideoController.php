<?php

namespace Controllers;

use Model\ProductoImgVideo;

class ProductoImgVideoController {
    
    public static function listarImgVideo(){
        
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        if(!is_numeric($_GET['id'])) return;

        $id = $_GET['id'];
        
        $consulta = "SELECT * FROM tblproducto_img_video WHERE ImVd_Id = " . $id;

        $registros=ProductoImgVideo::SQL($consulta);
      
        echo json_encode($registros);
    }
}


?>