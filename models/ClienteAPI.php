<?php

namespace Model;

class ClienteAPI extends ActiveRecord{
    protected static $tabla='cliente';
    protected static $columnasDB=['id_cliente','cedula_nit','nombre','telefono','direccion','fk_ciudad','nombre_ciudad','cod_depart','nombre_depart'];

    public $id_cliente;
    public $cedula_nit;
    public $nombre;
    public $telefono;
    public $direccion;
    public $fk_ciudad;
    public $nombre_ciudad;
    public $cod_depart;
    public $nombre_depart;
}

?>