<?php 

require 'funciones.php';
require 'database.php';
require __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set("America/Bogota"); // Configurar la zona horaria de Colombia

// Conectarnos a la base de datos
use Model\ActiveRecord;
ActiveRecord::setPDO($pdo);