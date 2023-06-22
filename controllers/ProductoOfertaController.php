<?php

namespace Controllers;

use Model\ProductoOferta;

class ProductoOfertaController{

    public static function listarOfertas(){
        
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $id = $_GET['id'];

        //2. Verificar si hay un elemento get y es un número
        if(!is_numeric($id)) return;

        //3. 
        $consultaOfertas = "SELECT PO_Id, PO_Cant, PO_ValorOferta, PO_FkProducto_Id FROM tblproducto_oferta WHERE PO_FkProducto_Id = " . $id;
        $resultadoOfertas = ProductoOferta::SQL($consultaOfertas);

        echo json_encode($resultadoOfertas);
        
    }

    public static function guardarAPI(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $oferta = new ProductoOferta();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //1. Primero sincronizamos los datos
            $oferta->sincronizar($_POST);

            //2.Validamos si no hay errores en la digitación del usuario
            $alertas = $oferta->validar();

            //3. Si hay alertas se termina la ejecución del código
            if(empty($alertas)){
                
                // Verificamos que no exista 
                $consulta = "SELECT * FROM tblproducto_oferta WHERE PO_Cant = " . $oferta->PO_Cant . " AND PO_ValorOferta = " . $oferta->PO_ValorOferta . " AND PO_FkProducto_Id = " . $oferta->PO_FkProducto_Id;
                                
                $resultado = ProductoOferta::SQL($consulta);
                // debuguear($resultado);
                if($resultado){
                    // retornamos false, porque ya existe un registro, por lo tanto no se puede guardar el mismo registro dos veces
                    // el regitro si existe
                    echo json_encode(false);
                }else{
                    // El registro no existe en la base de datos se procede a guardar
                    $resultado = $oferta->guardar(); 
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
        $productoOferta = ProductoOferta::find($id);
        $resultado = $productoOferta->eliminar($productoOferta->PO_Id);
        echo json_encode($resultado);
    }
}

?>