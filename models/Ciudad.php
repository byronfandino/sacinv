<?php

namespace Model;

class Ciudad extends ActiveRecord{

    protected static $tabla = 'tblciudad';
    protected static $columnasDB = ['Ciud_Id', 'Ciud_Nombre','Ciud_CodDepart'];

    public $Ciud_Id;
    public $Ciud_Nombre;
    public $Ciud_CodDepart;

    public function __construct($args = [])
    {

        $this->Ciud_Id = $args['Ciud_Id'] ?? null;
        $this->Ciud_Nombre = $args['Ciud_Nombre'] ?? '';
        $this->Ciud_CodDepart = $args['Ciud_CodDepart'] ?? '';

    }
}

?>