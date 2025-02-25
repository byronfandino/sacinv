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
            // $sqlCiudad="SELECT id_ciudad, nombre_ciudad, cod_depart, nombre_depart FROM ciudad INNER JOIN departamento ON ciudad.codigo_depart = departamento.cod_depart WHERE codigo_depart='".$codDepart."' ORDER BY nombre_ciudad";
            $sqlCiudad="SELECT id_ciudad, nombre_ciudad FROM ciudad WHERE codigo_depart='".$codDepart."' ORDER BY nombre_ciudad";
            $ciudades = Ciudad::SQL($sqlCiudad);

            echo json_encode($ciudades);
        }
        // $_GET['prueba'] = 1;
        // echo json_encode($_GET);

        // // if (isset($_GET['cod_depart'])) {
        // //     $codDepart = $_GET['cod_depart'];
            
        // //     // Depuración: Ver qué está recibiendo el backend
        // //     echo json_encode(["recibido" => $codDepart]);
        // //     exit;
        
        // //     $sqlCiudad="SELECT id_ciudad, nombre_ciudad FROM ciudad WHERE codigo_depart='".$codDepart."' ORDER BY nombre_ciudad";
        // //     $ciudades = Ciudad::SQL($sqlCiudad);
        
        // //     echo json_encode($ciudades);
        // // } else {
        // //     echo json_encode(["error" => "Falta el parámetro cod_depart"]);
        // // }
    }
}


?>