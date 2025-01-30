<?php 

require_once __DIR__ . '/../includes/app.php';

// use Controllers\CategoriaController;

use MVC\Router;
use Controllers\CiudadController;
use Controllers\ClienteAPIController;
use Controllers\ClienteController;
use Controllers\DeudaController;


$router = new Router();


//Deuda
$router->get('/', [DeudaController::class, 'inicio']);
$router->post('/deuda/guardar', [DeudaController::class, 'guardar']);
$router->post('/deuda/actualizar', [DeudaController::class, 'actualizar']);
$router->post('/deuda/eliminar', [DeudaController::class, 'eliminar']);

$router->get('/ciudad/api', [CiudadController::class, 'listarCiudades']);

// Cliente
$router->get('/cliente', [ClienteController::class, 'inicio']);
$router->get('/cliente/api', [ClienteAPIController::class, 'listarClientes']);
$router->post('/cliente/guardar', [ClienteController::class, 'guardar']);
$router->post('/cliente/actualizar', [ClienteController::class, 'actualizar']);
$router->post('/cliente/eliminar', [ClienteController::class, 'eliminar']);


//Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();