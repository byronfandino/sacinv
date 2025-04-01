<?php

namespace Controllers;

use Model\ClienteAPI;

require_once '../includes/parameters.php';

header('Access-Control-Allow-Origin: ' . $urlJSON ); 
header('Content-Type: application/json');

class ClienteAPIController{
    
    public static function listarClientes(){
        $sqlCliente ="SELECT id_cliente, cedula_nit, nombre, telefono, direccion, email, fk_ciudad, nombre_ciudad, cod_depart, nombre_depart FROM cliente as cl, ciudad as cd, departamento as dp WHERE cl.fk_ciudad = cd.id_ciudad AND dp.cod_depart = cd.codigo_depart ORDER BY nombre";
        
        $clientes = ClienteAPI::SQL($sqlCliente);
        echo json_encode($clientes);
    }
}

?>