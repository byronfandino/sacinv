<?php

namespace Model;

class Cliente extends ActiveRecord{

    protected static $tabla = 'tblcliente';
    protected static $columnasDB = ['Cli_Id', 'Cli_RazonSocial','Cli_Ced_Nit','Cli_NumVerif','Cli_TipoCliente','Cli_Tel','Cli_Email','Cli_Direccion','Cli_FkCiud_Id','Cli_Status'];
        
    public $Cli_Id;
    public $Cli_RazonSocial;
    public $Cli_Ced_Nit;
    public $Cli_NumVerif;
    public $Cli_TipoCliente;
    public $Cli_Tel;
    public $Cli_Email;
    public $Cli_Direccion;
    public $Cli_FkCiud_Id;
    public $Cli_Status;

    public function __construct($args = [])
    {
        $this->Cli_Id = $args['$Cli_Id'] ?? null;
        $this->Cli_RazonSocial = $args['$Cli_RazonSocial'] ?? '';
        $this->Cli_Ced_Nit = $args['$Cli_Ced_Nit'] ?? '';
        $this->Cli_NumVerif = $args['$Cli_NumVerif'] ?? '';
        $this->Cli_TipoCliente = $args['$Cli_TipoCliente'] ?? '';
        $this->Cli_Tel = $args['$Cli_Tel'] ?? '';
        $this->Cli_Email = $args['$Cli_Email'] ?? '';
        $this->Cli_Direccion = $args['$Cli_Direccion'] ?? '';
        $this->Cli_FkCiud_Id = $args['$Cli_FkCiud_Id'] ?? '';
        $this->Cli_Status = $args['$Cli_Status'] ?? 'E';

    }

    public function validar(){
        // 1. El usuario no digitó nada
        if (!$this->Cli_RazonSocial){

            self::$alertas['error-cliente']['razonsocial'][]="El campo Nombre - Razon Social es obligatorio";

        }else if (!preg_match('/^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{6,40}$/', $this->Cli_RazonSocial)){

            self::$alertas['error-cliente']['razonsocial'][]="Solo debe contener letras y números, mayor a 6 caracteres";

        }else{
            // Borramos los espacios de los lados
            $this->Cli_RazonSocial = trim($this->Cli_RazonSocial);
        }

        // Cédula
        if (!$this->Cli_Ced_Nit){

            self::$alertas['error-cliente']['cedulaNit'][]="El campo Cédula/Nit es obligatorio";

        }else if (!preg_match('/^[0-9-]{4,15}$/', $this->Cli_Ced_Nit)){

            self::$alertas['error-cliente']['cedulaNit'][]="Solo debe contener números, mayor a 4 caracteres";

        }else{
            // Borramos los espacios de los lados
            $this->Cli_Ced_Nit = trim($this->Cli_Ced_Nit);
        }

        // numero Verficacion del nit (Este campo NO es obligatorio)
        if (!$this->Cli_Ced_Nit){

            self::$alertas['error-cliente']['cedulaNit'][]="El campo Cédula/Nit es obligatorio";

        }else if (!preg_match('/^[0-9-]{4,15}$/', $this->Cli_Ced_Nit)){

            self::$alertas['error-cliente']['cedulaNit'][]="Solo debe contener números, mayor a 4 caracteres";

        }else{
            // Borramos los espacios de los lados
            $this->Cli_Ced_Nit = trim($this->Cli_Ced_Nit);
        }
        
        return self::$alertas;
    }
}

?>