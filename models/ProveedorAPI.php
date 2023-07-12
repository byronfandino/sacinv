<?php

namespace Model;

class ProveedorAPI extends ActiveRecord{

    // protected static $tabla = 'tblproducto';
    protected static $columnasDB = ['Prov_Id', 'Prov_RazonSocial', 'Prov_Nit', 'Prov_Tel', 'Prov_Email', 'Prov_Direccion', 'Prov_FkCiud_Id', 'Ciud_Nombre', 'Ciud_CodDepart', 'Depart_Nombre', 'Prov_Status'];
    
    public $Prov_Id;
    public $Prov_RazonSocial;
    public $Prov_Nit;
    public $Prov_Tel;
    public $Prov_Email;
    public $Prov_Direccion;
    public $Prov_FkCiud_Id;
    public $Ciud_Nombre;
    public $Ciud_CodDepart;
    public $Depart_Nombre;
    public $Prov_Status;

    public function __construct($args = [])
    { 
        $this->Prov_Id = $args['Prov_Id'] ?? null;
        $this->Prov_RazonSocial = $args['Prov_RazonSocial'] ?? '';
        $this->Prov_Nit = $args['Prov_Nit'] ?? '';
        $this->Prov_Tel = $args['Prov_Tel'] ?? '';
        $this->Prov_Email = $args['Prov_Email'] ?? '';
        $this->Prov_Direccion = $args['Prov_Direccion'] ?? '';
        $this->Prov_FkCiud_Id = $args['Prov_FkCiud_Id'] ?? '';
        $this->Ciud_Nombre = $args['Ciud_Nombre'] ?? '';
        $this->Ciud_CodDepart = $args['Ciud_CodDepart'] ?? '';
        $this->Depart_Nombre = $args['Depart_Nombre'] ?? '';
        $this->Prov_Status = $args['Prov_Status'] ?? '';

    }
}


?>