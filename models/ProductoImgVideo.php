<?php

namespace Model;

class ProductoImgVideo extends ActiveRecord{

    protected static $tabla = 'tblproducto_img_video';
    protected static $columnasDB = ['ImVd_Id', 'ImVd_Nombre', 'ImVd_FkProd_Id'];

    public $ImVd_Id;
    public $ImVd_Nombre;
    public $ImVd_FkProd_Id;

    public function __construct($args = [])
    {
        $this->ImVd_Id=$args['ImVd_Id'] ?? null;
        $this->ImVd_Nombre=$args['ImVd_Nombre'] ?? '';
        $this->ImVd_FkProd_Id=$args['ImVd_FkProd_Id'] ?? '';
    }

    public function organizarFiles($files){
        foreach($files as $archivo){

            for ($i=0; $i<count($archivo["name"]); $i++){
                $arrayFile[$i]['name'] = $archivo["name"][$i];
                $arrayFile[$i]['type'] = $archivo["type"][$i];
                $arrayFile[$i]['size'] = $archivo["size"][$i];
                $arrayFile[$i]['tmp_name'] = $archivo["tmp_name"][$i];
                $arrayFile[$i]['error'] = $archivo["error"][$i];
            }
        }
        return $arrayFile;
    }

    public function verificarArchivo($archivo){
        // echo '<pre>';
        // echo var_dump($archivo);
        // echo '</pre>';
        
        if($archivo["name"] != ""){

            if(!($archivo['type'] == "image/png" || $archivo['type'] == "image/jpg" || $archivo['type'] == "image/jpeg" || $archivo['type'] == "video/mp4")){
    
                self::$alertas['error-producto']['archivo'][]="Este tipo de archivo no está permitido";
                
            }else if($archivo['type'] == "video/mp4" && $archivo['size'] > 9500000){
                
                self::$alertas['error-producto']['archivo'][]="El video no debe superar las 4 MB";
                
            }else if(($archivo['type'] == "image/png" || $archivo['type'] == "image/jpg" || $archivo['type'] == "image/jpeg") && $archivo['size'] > 3000000){
                
                self::$alertas['error-producto']['archivo'][]="La imagen no debe superar los 500 KB";
            }
        }

        return self::$alertas;
    }

    // agregar archivo
    public function setArchivo($nombreArchivo){
        if($nombreArchivo){
            $this->ImVd_Nombre = $nombreArchivo;
        }
    }

    // agregar llave foránea
    public function setFkId($id){
        $this->ImVd_FkProd_Id = $id;
    }


}

?>