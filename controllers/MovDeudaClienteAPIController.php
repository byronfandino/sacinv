<?php

namespace Controllers;

use Model\DeudaMovimiento;

require_once '../includes/parameters.php';

header('Access-Control-Allow-Origin: ' . $urlJSON ); 
header('Content-Type: application/json');

class MovDeudaClienteAPIController {
    public static function listarMovDedudaCliente(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $sql ="SELECT id_mov, fk_deuda, fecha, hora, tipo_mov, descripcion, valor, saldo FROM deuda_movimiento WHERE fk_deuda = ( SELECT id_deuda FROM deuda WHERE fk_cliente = " . (int) $_POST['id']. " ORDER BY id_deuda DESC LIMIT 1) OFFSET 1";
            
            $deuda_mov = DeudaMovimiento::SQL($sql);

            echo json_encode($deuda_mov);
        }

    }
}

?>