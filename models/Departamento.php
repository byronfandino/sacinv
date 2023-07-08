<?php

namespace Model;

class Departamento extends ActiveRecord{

    protected static $tabla = 'tbldepartamento';
    protected static $columnasDB = ['Depart_Id', 'Depart_Codigo','Depart_Nombre'];
        
    public $Depart_Id;
    public $Depart_Codigo;
    public $Depart_Nombre;

    public function __construct($args = [])
    {

        $this->Depart_Id = $args['Depart_Id'] ?? null;
        $this->Depart_Codigo = $args['Depart_Codigo'] ?? '';
        $this->Depart_Nombre = $args['Depart_Nombre'] ?? '';

    }

}

?>