<?php

namespace Controllers;

use Model\ProductoCodigo;

class ProductoCodigoController{
    
    public static function listarCodigos(){
        
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $id = $_GET['id'];

        //2. Verificar si hay un elemento get y es un número
        if(!is_numeric($id)) return;

        //3. 
        $consultaCodigos = "SELECT Cod_Id, Cod_Barras, Cod_Manual FROM tblproducto_codigo WHERE Cod_FkProd_Id = " . $id;
        $resultadoCodigos = ProductoCodigo::SQL($consultaCodigos);

        echo json_encode($resultadoCodigos);
        
    }
    
    public static function guardarAPI(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $productoCodigo = new ProductoCodigo();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //1. Primero sincronizamos los datos
            $productoCodigo->sincronizar($_POST);

            //2.Validamos si no hay errores en la digitación del usuario
            $alertas = $productoCodigo->validar();

            //3. Si hay alertas se termina la ejecución del código
            if(empty($alertas)){
                
                $consulta = "SELECT * FROM tblproducto_codigo WHERE Cod_Barras = '" . $productoCodigo->Cod_Barras . "' AND Cod_Manual = '" . $productoCodigo->Cod_Manual . "' AND Cod_FkProd_Id = " . $productoCodigo->Cod_FkProd_Id;
                                
                $resultado = ProductoCodigo::SQL($consulta);
                // debuguear($resultado);
                if($resultado){
                    // retornamos false, porque ya existe un registro, por lo tanto no se puede guardar el mismo registro dos veces
                    // el regitro si existe
                    echo json_encode(false);
                }else{
                    // El registro no existe en la base de datos se procede a guardar
                    $resultado = $productoCodigo->guardar(); 
                    echo json_encode($resultado);
                }
            }else{
                echo json_encode(false);
            }
        }
    }

    public static function eliminar(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }
        
        $id=$_POST['id'];
        $productoCodigo = ProductoCodigo::find($id);
        $resultado = $productoCodigo->eliminar($productoCodigo->Cod_Id);
        echo json_encode($resultado);
    }
}


?>