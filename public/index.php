<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\CategoriaController;
use Controllers\CiudadController;
use Controllers\ClienteController;
use Controllers\CompraMasterController;
use Controllers\LoginController;
use Controllers\MarcaController;
use Controllers\MetodoPagoController;
use Controllers\PanelAdminController;
use Controllers\PanelUsuarioController;
use Controllers\ParametroController;
use Controllers\ProductoCodigoController;
use Controllers\ProductoController;
use Controllers\ProductoImgVideoController;
use Controllers\ProductoOfertaController;
use Controllers\ProveedorController;
use Controllers\TablaController;
use Controllers\UbicacionController;
use Controllers\UsuarioController;
use MVC\Router;

$router = new Router();

//Iniciar sesión
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

//Recuperar password
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide',[LoginController::class, 'olvide']);
$router->get('/recuperar', [LoginController::class, 'recuperar']);
$router->post('/recuperar', [LoginController::class, 'recuperar']);

//Crear cuenta
$router->get('/crear-cuenta', [LoginController::class,'crear']);
$router->post('/crear-cuenta', [LoginController::class,'crear']);

//Confirmar cuenta
$router->get('/mensaje', [LoginController::class,'mensaje']);
$router->get('/confirmar-cuenta', [LoginController::class,'confirmar']);

//Envio de correo por parte del usuario
$router->post('/envio-mensaje', [LoginController::class,'envioMensaje']);
$router->get('/mensaje-enviado', [LoginController::class,'mensajeEnviado']);
$router->get('/mensaje-recuperacion', [LoginController::class,'mensajeRecuperacion']);

//Ingreso al panel de trabajo
$router->get('/panel-admin', [PanelAdminController::class,'index']);
$router->get('/panel-usuario', [PanelUsuarioController::class,'index']);

//USUARIO - API
$router->get('/usuario/api', [UsuarioController::class,'listarNicknames']);

// Categoria
$router->get('/categoria', [CategoriaController::class,'index']);
$router->post('/categoria', [CategoriaController::class,'index']);
$router->get('/categoria/api', [CategoriaController::class,'listarCategorias']);
$router->post('/categoria/api', [CategoriaController::class,'guardarAPI']);
$router->post('/categoria/estado', [CategoriaController::class,'estado']);
$router->get('/categoria/editar', [CategoriaController::class,'editar']);
$router->post('/categoria/editar', [CategoriaController::class,'editar']);
$router->post('/categoria/eliminar', [CategoriaController::class,'eliminar']);

// Marca
$router->get('/marca/api', [MarcaController::class,'listarMarcas']);
$router->post('/marca/api', [MarcaController::class,'guardarAPI']);
$router->get('/marca', [MarcaController::class,'index']);
$router->post('/marca', [MarcaController::class,'index']);
$router->get('/marca/editar', [MarcaController::class,'editar']);
$router->post('/marca/editar', [MarcaController::class,'editar']);
$router->post('/marca/estado', [MarcaController::class,'estado']);
$router->post('/marca/eliminar', [MarcaController::class,'eliminar']);

// Ubicación
$router->get('/ubicacion/api', [UbicacionController::class,'listarUbicaciones']);
$router->get('/ubicacion', [UbicacionController::class,'index']);
$router->post('/ubicacion', [UbicacionController::class,'index']);
$router->get('/ubicacion/editar', [UbicacionController::class,'editar']);
$router->post('/ubicacion/editar', [UbicacionController::class,'editar']);
$router->post('/ubicacion/estado', [UbicacionController::class,'estado']);
$router->post('/ubicacion/eliminar', [UbicacionController::class,'eliminar']);

// Método de pago
$router->get('/metodo-pago/api', [MetodoPagoController::class,'listarMetodosPagos']);
$router->post('/metodo-pago/api', [MetodoPagoController::class,'guardarAPI']);
$router->get('/metodo-pago', [MetodoPagoController::class,'index']);
$router->post('/metodo-pago', [MetodoPagoController::class,'index']);
$router->get('/metodo-pago/editar', [MetodoPagoController::class,'editar']);
$router->post('/metodo-pago/editar', [MetodoPagoController::class,'editar']);
$router->post('/metodo-pago/estado', [MetodoPagoController::class,'estado']);
$router->post('/metodo-pago/eliminar', [MetodoPagoController::class,'eliminar']);

// Tablas
$router->get('/tabla/api', [TablaController::class,'listarTablas']);
$router->get('/tabla', [TablaController::class,'index']);
$router->post('/tabla', [TablaController::class,'index']);
$router->get('/tabla/editar', [TablaController::class,'editar']);
$router->post('/tabla/editar', [TablaController::class,'editar']);
$router->post('/tabla/estado', [TablaController::class,'estado']);
$router->post('/tabla/eliminar', [TablaController::class,'eliminar']);

// Parametro
$router->get('/parametro', [ParametroController::class,'index']);
$router->post('/parametro', [ParametroController::class,'index']);
$router->post('/parametro/eliminar-logo', [ParametroController::class, 'eliminarLogo']);

// Producto
$router->get('/producto/api', [ProductoController::class,'listarProductos']);
$router->get('/producto', [ProductoController::class,'index']);
$router->post('/producto', [ProductoController::class,'index']);
$router->post('/producto/estado', [ProductoController::class,'estado']);
$router->get('/producto/editar', [ProductoController::class,'editar']);
$router->post('/producto/editar', [ProductoController::class,'editar']);
$router->get('/producto/eliminar', [ProductoController::class,'eliminar']);
$router->post('/producto/eliminar', [ProductoController::class,'eliminar']);

// Producto Codigos
$router->get('/productocodigos/api', [ProductoCodigoController::class,'listarCodigos']);
$router->post('/productocodigos/api', [ProductoCodigoController::class,'guardarAPI']);
$router->post('/productocodigos/eliminar', [ProductoCodigoController::class,'eliminar']);

// Producto Ofertas
$router->get('/productoofertas/api', [ProductoOfertaController::class,'listarOfertas']);
$router->post('/productoofertas/api', [ProductoOfertaController::class,'guardarAPI']);
$router->post('/productoofertas/eliminar', [ProductoOfertaController::class,'eliminar']);

//Producto Imagenes/Videos
$router->get('/productoimgvideo/api', [ProductoImgVideoController::class,'listarArchivos']);
$router->post('/productoimgvideo/eliminar', [ProductoImgVideoController::class,'eliminar']);

// Ciudad
$router->get('/ciudad/api', [CiudadController::class,'listarCiudades']);


// Cliente
$router->get('/cliente/api', [ClienteController::class,'listar']);
$router->get('/cliente', [ClienteController::class,'index']);
$router->post('/cliente', [ClienteController::class,'index']);
$router->get('/cliente/editar', [ClienteController::class,'editar']);
$router->post('/cliente/editar', [ClienteController::class,'editar']);
$router->post('/cliente/estado', [ClienteController::class,'estado']);
$router->post('/cliente/eliminar', [ClienteController::class,'eliminar']);

// Proveedor
$router->get('/proveedor/api', [ProveedorController::class,'listar']);
$router->get('/proveedor', [ProveedorController::class,'index']);
$router->post('/proveedor', [ProveedorController::class,'index']);
$router->get('/proveedor/editar', [ProveedorController::class,'editar']);
$router->post('/proveedor/editar', [ProveedorController::class,'editar']);
$router->post('/proveedor/estado', [ProveedorController::class,'estado']);
$router->post('/proveedor/eliminar', [ProveedorController::class,'eliminar']);

// Compras
$router->get('/compra/api', [CompraMasterController::class,'listar']);
$router->get('/compra', [CompraMasterController::class,'index']);
$router->post('/compra', [CompraMasterController::class,'index']);
$router->get('/compra/editar', [CompraMasterController::class,'editar']);
$router->post('/compra/editar', [CompraMasterController::class,'editar']);
$router->post('/compra/estado', [CompraMasterController::class,'estado']);
$router->post('/compra/eliminar', [CompraMasterController::class,'eliminar']);

//Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();