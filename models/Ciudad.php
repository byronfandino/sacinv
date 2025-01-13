<?php

namespace Model;

class Ciudad extends ActiveRecord{
    protected static $tabla = 'ciudad';
    protected static $conlumnasDB=['id_ciudad', 'nombre_ciudad'];

    Public $id_ciudad;
    Public $nombre_ciudad;

}


?>