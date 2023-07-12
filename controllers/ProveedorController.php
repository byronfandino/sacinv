<?php

namespace Controllers;

use MVC\Router;
use Model\Ciudad;
use Model\Proveedor;
use Model\ProveedorAPI;
use Model\Departamento;

class ProveedorController{

    // API para mostrar el listado de categorias en formato JSON
    public static function listar (){
        
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $sql = "SELECT Prov_Id, Prov_RazonSocial, Prov_Nit, Prov_Tel, Prov_Email, Prov_Direccion, Prov_FkCiud_Id, Ciud_Nombre, Ciud_CodDepart, Depart_Nombre, Prov_Status 
        FROM tblproveedor, tblciudad, tbldepartamento 
        WHERE tblproveedor.Prov_FkCiud_Id=tblciudad.Ciud_Id 
        AND tblciudad.Ciud_CodDepart = tbldepartamento.Depart_Codigo  
        ORDER BY Prov_RazonSocial";
        $resultado = ProveedorAPI::SQL($sql);

        echo json_encode($resultado);

    }
    
    public static function index(Router $router){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $proveedor = new Proveedor();
        $alertas = [];
        $departamentos = Departamento::all('Depart_Nombre');
        // $ciudades = Ciudad::all('Ciudad_Nombre');

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            //1. Primero sincronizamos los datos
            $proveedor->sincronizar($_POST);

            //2.Validamos si no hay errores en la digitación del usuario
            $alertas = $proveedor->validar();

            //3. Si hay alertas se termina la ejecución del código
            if(empty($alertas)){
                
                //4. Procedemos a verificar que no exista en la base de datos
                $existe = Proveedor::where('Prov_Nit', trim($proveedor->Prov_Nit));

                if($existe){

                    Proveedor::setAlerta('error-proveedor', 'nit', 'Este proveedor ya existe en la base de datos');

                }else{
                    
                    // Si el registro no existe en la base de datos se procede a guardar
                    $resultado = $proveedor->guardar(); 
                    if($resultado){
                        Proveedor::setAlerta('exito-proveedor', 'general', 'El registro ha sido guardado satisfactoriamente');
                        $proveedor = new Proveedor();
                    }else{
                        
                        Proveedor::setAlerta('error-proveedor', 'general', 'No fue posible guardar el registro');
                    }
                    
                }
            }
        }
        $alertas=Proveedor::getAlertas();

        $router->renderPanel('panel/proveedor/index', [
            'proveedor' => $proveedor,
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
        $proveedor = Proveedor::find($_GET['id']);
        $departamentos = Departamento::all('Depart_Nombre');

        $fkDepart = $_GET['Ciud_CodDepart'];
        $sqlCiudades = "SELECT * FROM tblciudad WHERE Ciud_CodDepart = '" . $fkDepart . "' ORDER BY Ciud_Nombre";
        $ciudades = Ciudad::SQL($sqlCiudades);

        $alertas=[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            // 1. Sincronizar lo que digito el usuario en el objeto en memoria
            $proveedor->sincronizar($_POST);

            // 2. Validamos lo digitado por el usuario
            $alertas = $proveedor->validar();

            if(empty($alertas)){
                
                // Si el nombre de la descripción no existe en la base de datos se procede a guardar
                $resultado = $proveedor->guardar();

                if($resultado){
                    Proveedor::setAlerta('exito-proveedor', 'general', 'El registro ha sido guardado satisfactoriamente');
                }else{
                    Proveedor::setAlerta('error-proveedor', 'general', 'No fue posible guardar el registro. Posiblemente ya exista un registro con la misma descripción o no hay conexión con la base de datos');
                }
            }
            $alertas=Proveedor::getAlertas();
        }

        //4. Verificamos si realiza algún cambio en el método POST
        $router->renderPanel('panel/proveedor/editar',[
            'proveedor' => $proveedor,
            'departamentos' => $departamentos,
            'ciudades' => $ciudades,
            'alertas' => $alertas
        ]);
    }

    public static function estado(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $proveedor = Proveedor::find($_POST['id']);
        $proveedor->Prov_Status = $_POST['valor'];

        $resultado = $proveedor->guardar();
        echo json_encode($resultado);
    }

    public static function eliminar(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }
        
        $id = (int)$_POST['id'];
        $proveedor = Proveedor::find($id);
        if($proveedor){
            $resultado = $proveedor->eliminar($proveedor->Prov_Id);
        }
        echo json_encode($resultado);
    }
}

?>