<?php

namespace Controllers;

use MVC\Router;
use Model\Cliente;
use Model\ClienteAPI;
use Model\Departamento;

class ClienteController{

    // API para mostrar el listado de categorias en formato JSON
    public static function listar (){
        
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $sqlClientes = "SELECT Cli_Id, IF(Cli_TipoCliente = 'C', 'Corporativo', 'Persona Natural') AS Cli_TipoCliente, Cli_RazonSocial, Cli_Ced_Nit, Cli_Tel, Cli_Email, Cli_Direccion, Ciud_Nombre, Depart_Nombre, Cli_Status 
        FROM tblcliente, tblciudad, tbldepartamento 
        WHERE tblcliente.Cli_FkCiud_Id=tblciudad.Ciud_Id 
        AND tblciudad.Ciud_CodDepart = tbldepartamento.Depart_Codigo  
        ORDER BY Cli_RazonSocial";
        $resultado = ClienteAPI::SQL($sqlClientes);

        echo json_encode($resultado);

    }
    
    public static function index(Router $router){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $cliente = new Cliente();
        $alertas = [];
        $departamentos = Departamento::all('Depart_Nombre');
        // $ciudades = Ciudad::all('Ciudad_Nombre');

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            //1. Primero sincronizamos los datos
            $cliente->sincronizar($_POST);

            //2.Validamos si no hay errores en la digitación del usuario
            $alertas = $cliente->validar();

            //3. Si hay alertas se termina la ejecución del código
            if(empty($alertas)){
                
                //4. Procedemos a verificar que no exista en la base de datos
                $existe = Cliente::where('Cli_Ced_Nit', trim($cliente->Cli_Ced_Nit));

                if($existe){

                    Cliente::setAlerta('error-cliente', 'cedulaNit', 'Este cliente ya existe en la base de datos');

                }else{
                    
                    // Si el registro no existe en la base de datos se procede a guardar
                    $resultado = $cliente->guardar(); 
                    if($resultado){
                        Cliente::setAlerta('exito-cliente', 'general', 'El registro ha sido guardado satisfactoriamente');

                    }else{
                        
                        Cliente::setAlerta('error-cliente', 'general', 'No fue posible guardar el registro');
                    }
                    
                }
            }
        }
        $alertas=Cliente::getAlertas();

        // echo '<pre>';
        // echo var_dump($alertas);
        // echo '</pre>';
        // exit;
        $router->renderPanel('panel/cliente/index', [
            'cliente' => $cliente,
            'departamentos' => $departamentos,
            'alertas' => $alertas
        ]);
    }
    
    // public static function editar(Router $router){

    //     // 1. Verificar que el usuario haya iniciado sesión y que por el método get haya recibido 
    //     if(!isset($_SESSION['nombre']) || ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id']))){
    //         header('Location: /');
    //     }

    //     //2. Verificar si hay un elemento get y es un número
    //     if(!is_numeric($_GET['id'])) return;
        
    //     //3. Buscar el id de la Categoria y cargar el objeto en memoria
    //     $categoria = Categoria::find($_GET['id']);

    //     $alertas=[];

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    //         // 1. Sincronizar lo que digito el usuario en el objeto en memoria
    //         $categoria->sincronizar($_POST);

    //         // 2. Validamos lo digitado por el usuario
    //         $alertas = $categoria->validar();

    //         if(empty($alertas)){
                
    //             // Si el nombre de la descripción no existe en la base de datos se procede a guardar
    //             $resultado = $categoria->guardar();

    //             if($resultado){
    //                 Categoria::setAlerta('exito-categoria', 'general', 'El registro ha sido guardado satisfactoriamente');
    //             }else{
    //                 Categoria::setAlerta('error-categoria', 'general', 'No fue posible guardar el registro. Posiblemente ya exista un registro con la misma descripción o no hay conexión con la base de datos');
    //             }
    //         }
    //         $alertas=Categoria::getAlertas();
    //     }

    //     //4. Verificamos si realiza algún cambio en el método POST
    //     $router->renderPanel('panel/categoria/editar',[
    //         'categoria' => $categoria,
    //         'alertas' => $alertas
    //     ]);
    // }

    // public static function estado(){

    //     if(!isset($_SESSION['nombre'])){
    //         header('Location: /');
    //     }

    //     $categoria = new Categoria($_POST);
    //     $resultado = $categoria->guardar();

    //     echo json_encode($resultado);
    // }

    // public static function eliminar(Router $router ){

    //     if(!isset($_SESSION['nombre'])){
    //         header('Location: /');
    //     }
        
    //     $categoria = new Categoria($_POST);
    //     $resultado = $categoria->eliminar($categoria->Ctg_Id);
    //     echo json_encode($resultado);
    // }
}

?>