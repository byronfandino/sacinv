<?php

namespace Controllers;
use Model\API\Ciudad;
use Model\API\ClienteAPI;
use Model\API\UsuarioAPI;
use Model\DeudaMovimiento;
use Model\Tabla;

require_once '../includes/parameters.php';

header('Access-Control-Allow-Origin: ' . $urlJSON ); 
header('Content-Type: application/json');

class APIController{

    public static function listarUsuarios(){
        
        $sql="SELECT us.id_us, us.cedula_us, us.nombre_us, us.nickname_us, us.password_us, us.tipo_us, us.celular_us, us.email_us, us.direccion_us, us.status_us, us.fk_ciudad_us, cd.nombre_ciudad, cd.codigo_depart, dp.nombre_depart FROM usuario_sistema as us INNER JOIN ciudad as cd ON us.fk_ciudad_us = cd.id_ciudad INNER JOIN departamento as dp ON cd.codigo_depart = dp.cod_depart ORDER BY us.nombre_us";

        $resultado = UsuarioAPI::SQL($sql);
        echo json_encode($resultado);

    }

    public static function listarCiudades(){

        if (isset($_GET['cod_depart'])){
            $codDepart = $_GET['cod_depart'];
            $sqlCiudad="SELECT id_ciudad, nombre_ciudad FROM ciudad WHERE codigo_depart='".$codDepart."' ORDER BY nombre_ciudad";
            $ciudades = Ciudad::SQL($sqlCiudad);

            echo json_encode($ciudades);
        }
    }

    public static function listarClientes(){
        $sqlCliente ="SELECT id_cliente, cedula_nit, nombre, telefono, direccion, email, fk_ciudad, nombre_ciudad, cod_depart, nombre_depart FROM cliente as cl, ciudad as cd, departamento as dp WHERE cl.fk_ciudad = cd.id_ciudad AND dp.cod_depart = cd.codigo_depart ORDER BY nombre";
        
        $clientes = ClienteAPI::SQL($sqlCliente);
        echo json_encode($clientes);
    }

    public static function listarMovDedudaCliente(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $sql ="SELECT id_mov, fk_deuda, tipo_mov, descripcion, cant, valor_unit, valor_total, saldo, fecha, hora FROM deuda_movimiento WHERE fk_deuda = ( SELECT id_deuda FROM deuda WHERE fk_cliente = " . (int) $_POST['id']. " ORDER BY id_deuda DESC LIMIT 1) ORDER BY id_mov ASC OFFSET 1";
            
            $deuda_mov = DeudaMovimiento::SQL($sql);

            echo json_encode($deuda_mov);
        }

    }

    public static function listarTablas(){
        $sql ="SELECT * FROM tabla ORDER BY nombre_tb";
        
        $resultado = Tabla::SQL($sql);
        echo json_encode($resultado);
    }

}


?>