<?php

namespace Model;

class Departamento extends ActiveRecord{
    protected static $tabla = 'departamento';
    protected static $columnasDB = ['id_depart', 'cod_depart', 'nombre_depart'];

    Public $id_depart; 
    Public $cod_depart; 
    Public $nombre_depart;
}

?>
