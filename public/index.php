<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\APIController;
use Controllers\ClienteController;
use Controllers\DeudaController;
use Controllers\TablaController;

$router = new Router();

//API Ciudad
$router->get('/ciudad/api', [APIController::class, 'listarCiudades']);

// Cliente
$router->get('/cliente', [ClienteController::class, 'inicio']);
$router->get('/cliente/api', [APIController::class, 'listarClientes']);
$router->post('/cliente/guardar', [ClienteController::class, 'guardar']);
$router->post('/cliente/actualizar', [ClienteController::class, 'actualizar']);
$router->post('/cliente/eliminar', [ClienteController::class, 'eliminar']);

//Deuda
$router->get('/', [DeudaController::class, 'inicio']);
$router->post('/deuda_mov/cliente/api', [APIController::class, 'listarMovDedudaCliente']);
$router->post('/deuda/guardar', [DeudaController::class, 'guardar']);
$router->post('/deuda/actualizar', [DeudaController::class, 'actualizar']);
$router->post('/deuda/eliminar', [DeudaController::class, 'eliminar']);
$router->get('/deuda/reporte_general', [DeudaController::class, 'reporteCSV']);
$router->get('/deuda/reporte_deudores', [DeudaController::class, 'reporteDeudores']);
$router->get('/deuda/reporte_movimientos_deudor', [DeudaController::class, 'reporteMovimientosDeudor']);

//Tabla
$router->get('/tabla', [TablaController::class, 'inicio']);
$router->get('/tabla/api', [APIController::class, 'listartablas']);
$router->post('/tabla/guardar', [TablaController::class, 'guardar']);
$router->post('/tabla/actualizar', [TablaController::class, 'actualizar']);
$router->post('/tabla/eliminar', [TablaController::class, 'eliminar']);

//Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();