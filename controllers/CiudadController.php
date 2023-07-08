<?php

namespace Controllers;

use Model\Ciudad;

class CiudadController{

    // API para mostrar el listado de categorias en formato JSON
    public static function listarCiudades (){
        
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        //2. obtenemos el código del departamento 
        $fkDepart = $_GET['codDepart'];

        //3. Obtenemos las ciudades
        $sqlCiudades = "SELECT * FROM tblciudad WHERE Ciud_CodDepart = '" . $fkDepart . "' ORDER BY Ciud_Nombre";
        $resultCiudades = Ciudad::SQL($sqlCiudades);

        echo json_encode($resultCiudades);
        
    }
}

?>