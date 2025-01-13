<?php 

require_once __DIR__ . '/../includes/app.php';

// use Controllers\CategoriaController;

use MVC\Router;
use Controllers\InicioController;
use Controllers\CiudadController;
use Controllers\ClienteAPIController;
use Controllers\ClienteController;

$router = new Router();

//Iniciar sesión
$router->get('/', [InicioController::class, 'inicio']);
$router->get('/ciudad/api', [CiudadController::class, 'listarCiudades']);
$router->get('/cliente', [ClienteController::class, 'inicio']);
$router->get('/cliente/api', [ClienteAPIController::class, 'listarClientes']);
$router->post('/cliente/guardar', [ClienteController::class, 'guardar']);
$router->post('/cliente/actualizar', [ClienteController::class, 'actualizar']);
$router->post('/cliente/eliminar', [ClienteController::class, 'eliminar']);

//Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();