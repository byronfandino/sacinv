<?php

// if(isset($deudores)){
//     echo "<h1>Listado de deudores</h1>";
//     echo "<pre>";
//     echo var_dump($deudores);
//     echo "</pre>";
// }
$mpdf->Output("reporte_clientes.pdf", "I");
// $mpdf->Output("reporte_clientes.pdf", "D");

?>