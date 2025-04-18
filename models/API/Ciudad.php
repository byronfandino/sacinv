<?php

namespace Model\API;
use Model\ActiveRecord;

class Ciudad extends ActiveRecord{
    protected static $tabla = 'ciudad';
    protected static $columnasDB=['id_ciudad', 'nombre_ciudad'];

    Public $id_ciudad;
    Public $nombre_ciudad;
}


?>