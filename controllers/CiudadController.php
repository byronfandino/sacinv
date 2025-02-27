<?php

namespace Controllers;
use Model\Ciudad;

require_once '../includes/parameters.php';

header('Access-Control-Allow-Origin: ' . $urlJSON ); 
header('Content-Type: application/json');


class CiudadController{
    public static function listarCiudades(){

        if (isset($_GET['cod_depart'])){
            $codDepart = $_GET['cod_depart'];
            $sqlCiudad="SELECT id_ciudad, nombre_ciudad FROM ciudad WHERE codigo_depart='".$codDepart."' ORDER BY nombre_ciudad";
            $ciudades = Ciudad::SQL($sqlCiudad);

            echo json_encode($ciudades);
        }
    }
}


?>