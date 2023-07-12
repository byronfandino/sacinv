<?php

namespace Model;

class ClienteAPI extends ActiveRecord{

    // protected static $tabla = 'tblproducto';
    protected static $columnasDB = ['Cli_Id', 'Cli_TipoCliente', 'Nombre_TipoCliente', 'Cli_RazonSocial', 'Cli_Ced_Nit', 'Cli_Tel', 'Cli_Email', 'Cli_Direccion', 'Cli_FkCiud_Id', 'Ciud_Nombre', 'Ciud_CodDepart', 'Depart_Nombre', 'Cli_Status'];
    
    public $Cli_Id;
    public $Cli_TipoCliente;
    public $Nombre_TipoCliente;
    public $Cli_RazonSocial;
    public $Cli_Ced_Nit;
    public $Cli_Tel;
    public $Cli_Email;
    public $Cli_Direccion;
    public $Cli_FkCiud_Id;
    public $Ciud_Nombre;
    public $Ciud_CodDepart;
    public $Depart_Nombre;
    public $Cli_Status;

    public function __construct($args = [])
    { 
        $this->Cli_Id = $args['Cli_Id'] ?? null;
        $this->Cli_TipoCliente = $args['Cli_TipoCliente'] ?? '';
        $this->Nombre_TipoCliente = $args['Nombre_TipoCliente'] ?? '';
        $this->Cli_RazonSocial = $args['Cli_RazonSocial'] ?? '';
        $this->Cli_Ced_Nit = $args['Cli_Ced_Nit'] ?? '';
        $this->Cli_Tel = $args['Cli_Tel'] ?? '';
        $this->Cli_Email = $args['Cli_Email'] ?? '';
        $this->Cli_Direccion = $args['Cli_Direccion'] ?? '';
        $this->Cli_FkCiud_Id = $args['Cli_FkCiud_Id'] ?? '';
        $this->Ciud_Nombre = $args['Ciud_Nombre'] ?? '';
        $this->Ciud_CodDepart = $args['Ciud_CodDepart'] ?? '';
        $this->Depart_Nombre = $args['Depart_Nombre'] ?? '';
        $this->Cli_Status = $args['Cli_Status'] ?? '';
    }
}


?>