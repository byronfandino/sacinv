<?php

// Configurar encabezados HTTP para la descarga
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $archivo . '"');

// Abrir la salida estándar como archivo
$output = fopen('php://output', 'w');

// Ejecutar la consulta
$result = pg_query($conn, $consulta_sql);

if ($result) {
    // Obtener nombres de las columnas
    $columnas = array_keys(pg_fetch_assoc($result));
    fputcsv($output, $columnas); // Escribir encabezados en el CSV

    // Rewind para reiniciar el puntero y volver a recorrer los resultados
    pg_result_seek($result, 0);

    // Escribir los datos en el CSV
    while ($row = pg_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
}

// Cerrar conexión y salida
pg_close($conn);
fclose($output);
exit;
?>
