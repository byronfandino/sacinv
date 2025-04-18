<?php

namespace Controllers;

use Model\Tabla;

require_once '../includes/parameters.php';

header('Access-Control-Allow-Origin: ' . $urlJSON ); 
header('Content-Type: application/json');

class TablaAPIController{
    
    public static function listarTablas(){
        $sql ="SELECT * FROM tabla ORDER BY nombre_tb";
        
        $resultado = Tabla::SQL($sql);
        echo json_encode($resultado);
    }
}

?>