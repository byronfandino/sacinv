<?php

namespace Controllers;

use Intervention\Image\ImageManagerStatic as Image;
use MVC\Router;
use Model\Producto;
use Model\CompraDetalle;
use Model\InventarioFifo;
use Model\ProductoCodigo;
use Model\ProductoOferta;
use Model\ProductoImgVideo;
use Model\ProductoAPI;
use Model\VentaDetalle;

class ProductoController{

    // Esta función solo se utllizara para mostrar en tablas los items a mostrar
    public static function listarItems(){
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $id = $_GET['id'];

        //2. Verificar si hay un elemento get y es un número
        if(!is_numeric($id)) return;

        $consultaCodigos = "SELECT Cod_Id, Cod_Barras, Cod_Manual FROM tblproducto_codigo WHERE Cod_FkProd_Id = " . $id;
        $resultadoCodigos = ProductoCodigo::SQL($consultaCodigos);

        $consultaOfertas = "SELECT PO_Id, PO_Cant, PO_ValorOferta FROM tblproducto_oferta WHERE PO_FkProducto_Id = " . $id;
        $resultadoOfertas = ProductoOferta::SQL($consultaOfertas);

        $consultaImgVideos = "SELECT ImVd_Id, ImVd_Nombre FROM tblproducto_img_video WHERE ImVd_FkProd_Id = " . $id;
        $resultadoImgVideos = ProductoImgVideo::SQL($consultaImgVideos);

        echo json_encode([$resultadoCodigos,  $resultadoOfertas, $resultadoImgVideos]);
    }

    // API para mostrar el listado de categorias en formato JSON
    public static function listarProductos (){    
        
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $consulta = "SELECT prod.Prod_Id, cod.Cod_Manual, cod.Cod_Barras, prod.Prod_Descripcion, ctg.Ctg_Descripcion, mc.Mc_Descripcion, ";
        $consulta .="prod.Prod_ValorVenta, prod.Prod_ValorDesc, prod.Prod_CantMinStock, of.PO_Cant, of.PO_ValorOferta, prod.Prod_Status ";
        $consulta .="FROM tblproducto AS prod ";
        $consulta .="LEFT OUTER JOIN tblcategoria AS ctg ";
        $consulta .="ON prod.Prod_FkCtg_Id = ctg.Ctg_Id ";
        $consulta .="LEFT OUTER JOIN tblmarca AS mc ";
        $consulta .="ON prod.Prod_FkMc_Id = mc.Mc_Id ";
        $consulta .="LEFT OUTER JOIN tblproducto_codigo AS cod ";
        $consulta .="ON cod.Cod_FkProd_Id = prod.Prod_Id ";
        $consulta .="LEFT OUTER JOIN tblproducto_oferta AS of ";
        $consulta .="ON of.PO_FkProducto_Id = prod.Prod_Id ";
        $consulta .="ORDER BY prod.Prod_Descripcion";

        $registros=ProductoAPI::SQL($consulta);
        
        foreach ($registros as $registro){
            $registro->Prod_ValorVenta = number_format($registro->Prod_ValorVenta);
            $registro->Prod_ValorDesc = number_format($registro->Prod_ValorDesc);
            $registro->PO_ValorOferta = number_format($registro->PO_ValorOferta);
            $registro->PO_Cant = number_format($registro->PO_Cant);
        }
      
        echo json_encode($registros);
    }

    public static function index(Router $router){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        $producto = new Producto();
        $codigoProducto = new ProductoCodigo();
        $ofertasProducto = new ProductoOferta();
        $archivoProducto = new ProductoImgVideo();
        
        $alertas = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            //1. Primero sincronizamos los datos
            $producto->sincronizar($_POST);
            $codigoProducto->sincronizar($_POST);
            $ofertasProducto->sincronizar($_POST);

            $arrayFiles=[];//Almacenamos el arreglo ordenado de $_FILES
            
            //Verificamos si se adjunto algún archivo
            if(isset($_FILES['ImVd_Nombre']['name']) && !empty($_FILES['ImVd_Nombre']['name'])){
                
                // Organizamos el arreglo proveniente de $_FILES
                $arrayFiles = $archivoProducto->organizarFiles($_FILES);

                // Ejecutamos la validación de cada archivo subido por el usuario
                foreach($arrayFiles as $arreglo ){
                    $alertas = $archivoProducto->verificarArchivo($arreglo);
                }
            }
            
            // Verficamos si se agregó los códigos del producto
            $alertas = $ofertasProducto->validar();
            $alertas = $codigoProducto->validar();
            $alertas = $producto->validar();
            
            //2. Si hay alertas en todas las tablas
            if(empty($alertas)){
                
                //1. Guardamos en la tabla producto y obtenemos el id
                $resultadoProducto = $producto->guardar();
                $id = $resultadoProducto['id'];
               
                //2. Guardamos los codígos manual y de barras con el id del producto
                // Si guardó adecuadamente el producto, procedemos a guardar el código de barras
                $resultadoCodigo=false;

                if($resultadoProducto['resultado']){
                    //2.1 Agregamos la llave foránea del producto
                    $codigoProducto->setFkId($id);
                    $resultadoCodigo = $codigoProducto->guardar();
                    
                    if(!$resultadoCodigo['resultado']){
                        Producto::setAlerta('error-producto','codigo','No fue posible agregar el registro del producto');
                    }

                    //3. Verificamos si hay ofertas de venta para agregar un nuevo registro con el id del producto
                    if($ofertasProducto->PO_Cant != "" ){
                        
                        if($ofertasProducto->PO_Cant != "0" ){
                        
                            $ofertasProducto->setFkId($id);
                            $resultadoOferta = $ofertasProducto->guardar();
    
                            if(!$resultadoOferta['resultado']){
                                Producto::setAlerta('error-producto','oferta','Hubo un error para agregar la oferta a la base de datos igual a número');
                            }
                        }

                    }

                    //4. Verificamos si hay archivos adjuntos y en un arreglo se agregan todos los archivos al servidor y base de datos
                    if(isset($_FILES['ImVd_Nombre']['tmp_name'][0]) && $_FILES['ImVd_Nombre']['tmp_name'][0]!=""){

                        // en este punto sabemos si se escogió un archivo por lo tanto verificamos la existencia de la carpeta
                        if(!is_dir(CARPETA_PRODUCTOS)){
                            mkdir(CARPETA_PRODUCTOS);
                        }   

                        $j=0;

                        foreach($_FILES['ImVd_Nombre']['tmp_name'] as $archivo){

                            if ($_FILES['ImVd_Nombre']['tmp_name'][$j]){
    
                                $particion = explode("/",$_FILES['ImVd_Nombre']['type'][$j]);
                                $extension = $particion[1];
                                
                                $nombreArchivo=md5( uniqid( rand(), true ) );
    
                                if ($extension !== "mp4"){
                                    // Si es una imagen, se crea una copia optimizada
    
                                    $archivoOptimizado = Image::make($archivo)->resize(null, 150, function ($constraint) {
                                    //Realiza un rezise a la imagen con intervention
                                        $constraint->aspectRatio();
                                    });
    
                                    // Se guarda en el servidor el archivo optimizado
                                    $archivoOptimizado->save(CARPETA_PRODUCTOS . $nombreArchivo . "-opt." . $extension);
                                }
                                
                                // Guardamos el archivo sin optimizar al servidor
                                move_uploaded_file($archivo, CARPETA_PRODUCTOS . $nombreArchivo . "." . $extension);
                                
                                // Agregamos el nombre de la imagen y la llave foránea del producto
                                $archivoProducto->setArchivo($nombreArchivo . "." . $extension);
                                $archivoProducto->setFkId($id);
    
                                // Guardamos el registro en la base de datos
                                $resultadoImVd = $archivoProducto->guardar();
                                
                                $j++;
    
                                if(!$resultadoImVd['resultado']){
                                    Producto::setAlerta('error-producto','archivo','No fue posible agregar el registro del producto ' . $nombreArchivo);
                                }
                            }
                        }
                    }

                    Producto::setAlerta('exito-producto','general','Registro guardado satisfactoriamente');
                }else{
                    Producto::setAlerta('error-producto','general','No fue posible agregar el registro del producto');
                }
            }
        }

        $alertas=Producto::getAlertas();

        $router->renderPanel('/panel/productos/index', [
            'alertas' => $alertas
        ]);
    }

    public static function editar(Router $router){

        // 1. Verificar que el usuario haya iniciado sesión y que por el método get haya recibido 
        if(!isset($_SESSION['nombre']) || ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id']))){
            header('Location: /');
        }

        //2. Verificar si hay un elemento get y es un número
        if(!is_numeric($_GET['id'])) return;
        
        //3. Buscar el id de la Marca y cargar el objeto en memoria
        $producto = Producto::find($_GET['id']);

        $alertas=[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            // 1. Sincronizar lo que digito el usuario en el objeto en memoria
            $producto->sincronizar($_POST);

            // 2. Validamos lo digitado por el usuario
            $alertas = $producto->validar();

            if(empty($alertas)){
                
                // 3. Si el nombre de la descripción no existe en la base de datos se procede a guardar
                $resultado = $producto->guardar();

                if($resultado){
                    Producto::setAlerta('exito-producto', 'general', 'El registro ha sido guardado satisfactoriamente');
                }else{
                    Producto::setAlerta('error-producto', 'general', 'No fue posible guardar el registro. Posiblemente ya exista un registro con la misma descripción o no hay conexión con la base de datos');
                }
            }
            $alertas=Producto::getAlertas();
        }

        $producto->Prod_ValorVenta = number_format($producto->Prod_ValorVenta, 0, '','');
        $producto->Prod_ValorDesc = number_format($producto->Prod_ValorDesc, 0, '','');

        echo '<pre>';
        echo var_dump($producto);
        echo '</pre>';
        
        //4. Verificamos si realiza algún cambio en el método POST
        $router->renderPanel('panel/productos/editar',[
            'producto' => $producto,
            'alertas' => $alertas
        ]);
    }

    public static function estado(){
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }
        
        $producto = Producto::find($_POST['id']);
        $producto->Prod_Status = $_POST['valor'];
        
        $resultado = $producto->guardar();
        echo json_encode($resultado);
    }

    public static function eliminar(){

        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }
        
        $resultado=false;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = (int)$_POST['id'];
            $producto = Producto::find($id);
            
            if ($producto){
                // Como el producto si existe, entonces procedemos a eliminar todos los registros indexados en las tablas
                //1. Primero verificamos que no se encuentra en un tabla mayor, como ventas, compras, e inventario, no se relaciona con todos los inventarios debido a que todos se retroalimentarán al tiempo, por lo tanto con solo ver un inventario es suficiente.
                //De igual manera que la tabla pendientes, ya que si se encuentran llaves foráneas en esta tabla quiere decir que ya existen registros en las tablas de compras y ventas
                $arrayVenta = VentaDetalle::existeLlaveForanea('VD_FkProd_Id', $id);
                $arrayCompra = CompraDetalle::existeLlaveForanea('CD_FkProd_Id', $id);
                $arrayInventario = InventarioFifo::existeLlaveForanea('Inv_FkProducto_Id', $id);
                
                if(sizeof($arrayVenta[0]) === 0 && sizeof($arrayCompra[0]) === 0 && sizeof($arrayInventario[0]) === 0 ){
                    
                    // Si no existen llaves foráneas... procedemos a eliminar el registro de las llaves foráneas del producto que se encuentran vinculadas a las tablas básicas
                    //El primero es eliminar los registros asociados a la tabla imgvideo
                    $arrayProdImgVideo = ProductoImgVideo::existeLlaveForanea('ImVd_FkProd_Id', $id);
                    
                    if(!empty($arrayProdImgVideo[0])){

                        // eliminamos en primer lugar el registro en la base de datos
                        foreach($arrayProdImgVideo[0] as $item){
                            $registroImgVideo = ProductoImgVideo::find($item);
                            // Si existe el registro se elimina 
                            if ($registroImgVideo){
                                $rta = $registroImgVideo->eliminar($item);
                            }
                        }

                        // luego eliminamos los archivos del servidor
                        foreach ($arrayProdImgVideo[1] as $item){
                            $existe = file_exists(CARPETA_PRODUCTOS . $item);
                            // Si existe el archivo se elimina del servidor
                            if ($existe){
                                //1. Eliminamos el archivo original
                                unlink(CARPETA_PRODUCTOS . $item);
                                
                                //2. Luego eliminamos el archivo optimizado en caso que exista (porque la excepción son los videos)
                                $dividirArchivo = explode(".", $item);
                                $existe = file_exists(CARPETA_PRODUCTOS . $dividirArchivo[0] . "-opt." . $dividirArchivo[1]);

                                if ($existe){
                                    unlink(CARPETA_PRODUCTOS . $dividirArchivo[0] . "-opt." . $dividirArchivo[1]);
                                }
                            }
                        }
                    }
                    
                    $arrayProdCodigo = ProductoCodigo::existeLlaveForanea('Cod_FkProd_Id', $id);

                    if(!empty($arrayProdCodigo[0])){

                        foreach($arrayProdCodigo[0] as $item){

                            $registroCodigo = ProductoCodigo::find($item);

                            // Si existe el registro se elimina 
                            if ($registroCodigo){
                                $rta = $registroCodigo->eliminar($item);
                            }
                        }

                        // Procedemos a eliminar la Oferta
                        $arrayProdOferta = ProductoOferta::existeLlaveForanea('PO_FkProducto_Id', $id);

                        if(!empty($arrayProdOferta[0])){
                            // Obtenemos el objeto
                            $registroOferta = ProductoOferta::find($arrayProdOferta[0][0]);

                            if($registroOferta){
                                $rta =  $registroOferta->eliminar($arrayProdOferta[0][0]);
                            }
                        }
                    }

                    //3. Por último eliminamos el ptoducto de la base de datos
                    $resultado = $producto->eliminar($producto->Prod_Id);

                }else{
                    $resultado = false;
                }
            }
        }

        echo json_encode($resultado);

    }
}

?>