<?php

namespace Model;

class ClienteAPI extends ActiveRecord{

    // protected static $tabla = 'tblproducto';
    protected static $columnasDB = ['Cli_Id', 'Cli_TipoCliente', 'Cli_RazonSocial', 'Cli_Ced_Nit', 'Cli_Tel', 'Cli_Email', 'Cli_Direccion', 'Ciud_Nombre', 'Depart_Nombre', 'Cli_Status'];
    
    public $Cli_Id;
    public $Cli_TipoCliente;
    public $Cli_RazonSocial;
    public $Cli_Ced_Nit;
    public $Cli_Tel;
    public $Cli_Email;
    public $Cli_Direccion;
    public $Ciud_Nombre;
    public $Depart_Nombre;
    public $Cli_Status;

    public function __construct($args = [])
    { 
        $this->Cli_Id = $args['Cli_Id'] ?? null;
        $this->Cli_TipoCliente = $args['Cli_TipoCliente'] ?? '';
        $this->Cli_RazonSocial = $args['Cli_RazonSocial'] ?? '';
        $this->Cli_Ced_Nit = $args['Cli_Ced_Nit'] ?? '';
        $this->Cli_Tel = $args['Cli_Tel'] ?? '';
        $this->Cli_Email = $args['Cli_Email'] ?? '';
        $this->Cli_Direccion = $args['Cli_Direccion'] ?? '';
        $this->Ciud_Nombre = $args['Ciud_Nombre'] ?? '';
        $this->Depart_Nombre = $args['Depart_Nombre'] ?? '';
        $this->Cli_Status = $args['Cli_Status'] ?? '';
    }
}


?>