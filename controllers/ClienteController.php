<?php

namespace Controllers;

use MVC\Router;
use Model\Ciudad;
use Model\Cliente;
use Model\ClienteAPI;
use Model\Departamento;

class ClienteController{

    // API para mostrar el listado de categorias en formato JSON
    public static function listar (){
        
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $sqlClientes = "SELECT Cli_Id, Cli_TipoCliente, IF(Cli_TipoCliente = 'C', 'Corporativo', 'Persona Natural') AS Nombre_TipoCliente, Cli_RazonSocial, Cli_Ced_Nit, Cli_Tel, Cli_Email, Cli_Direccion, Cli_FkCiud_Id, Ciud_Nombre, Ciud_CodDepart, Depart_Nombre, Cli_Status 
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
                        $cliente = new Cliente();
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
    
    public static function editar(Router $router){

        // 1. Verificar que el usuario haya iniciado sesión y que por el método get haya recibido 
        if(!isset($_SESSION['nombre']) || ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id']))){
            header('Location: /');
        }

        //2. Verificar si hay un elemento get y es un número
        if(!is_numeric($_GET['id']) || !is_numeric($_GET['Ciud_CodDepart'])) return;
        
        //3. Buscar el id de la Categoria y cargar el objeto en memoria
        $cliente = Cliente::find($_GET['id']);
        $departamentos = Departamento::all('Depart_Nombre');

        $fkDepart = $_GET['Ciud_CodDepart'];
        $sqlCiudades = "SELECT * FROM tblciudad WHERE Ciud_CodDepart = '" . $fkDepart . "' ORDER BY Ciud_Nombre";
        $ciudades = Ciudad::SQL($sqlCiudades);

        $alertas=[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            // 1. Sincronizar lo que digito el usuario en el objeto en memoria
            $cliente->sincronizar($_POST);

            // 2. Validamos lo digitado por el usuario
            $alertas = $cliente->validar();

            if(empty($alertas)){
                
                // Si el nombre de la descripción no existe en la base de datos se procede a guardar
                $resultado = $cliente->guardar();

                if($resultado){
                    Cliente::setAlerta('exito-cliente', 'general', 'El registro ha sido guardado satisfactoriamente');
                }else{
                    Cliente::setAlerta('error-cliente', 'general', 'No fue posible guardar el registro. Posiblemente ya exista un registro con la misma descripción o no hay conexión con la base de datos');
                }
            }
            $alertas=Cliente::getAlertas();
        }

        //4. Verificamos si realiza algún cambio en el método POST
        $router->renderPanel('panel/cliente/editar',[
            'cliente' => $cliente,
            'departamentos' => $departamentos,
            'ciudades' => $ciudades,
            'alertas' => $alertas
        ]);
    }

    public static function estado(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $cliente = Cliente::find($_POST['id']);
        $cliente->Cli_Status = $_POST['valor'];

        $resultado = $cliente->guardar();
        echo json_encode($resultado);
    }

    public static function eliminar(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }
        
        $id = (int)$_POST['id'];
        $cliente = Cliente::find($id);
        if($cliente){
            $resultado = $cliente->eliminar($cliente->Cli_Id);
        }
        echo json_encode($resultado);
    }
}

?>