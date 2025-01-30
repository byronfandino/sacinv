<?php
// Datos de conexión
$host = 'localhost'; // Dirección del servidor de la base de datos
$db = 'dbdeudores'; // Nombre de tu base de datos
$user = 'bfandino'; // Tu usuario de la base de datos
$password = 'Laura0405@'; // Tu contraseña de la base de datos
$port = '5432'; // Puerto por defecto de PostgreSQL

try {
    // Crear la cadena de conexión
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";

    // Opciones de configuración de PDO
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Manejo de errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Modo de obtención de resultados
        PDO::ATTR_EMULATE_PREPARES => false, // Emulación de declaraciones preparadas
    ];

    // Crear una nueva instancia de PDO
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    // Manejar cualquier error de conexión
    // echo "Error en la conexión: " . $e->getMessage();
    // echo json_encode([
    //     "rta" => "false",
    //     "message" => "Error inesperado: " . $e->getMessage()
    // ]);
    return $e->getMessage();
}
?>
