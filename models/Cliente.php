<?php

namespace Model;

class Cliente extends ActiveRecord{

    protected static $tabla = 'tblcliente';
    protected static $columnasDB = ['Cli_Id', 'Cli_RazonSocial','Cli_Ced_Nit','Cli_TipoCliente','Cli_Tel','Cli_Email','Cli_Direccion','Cli_FkCiud_Id','Cli_Status'];
        
    public $Cli_Id;
    public $Cli_RazonSocial;
    public $Cli_Ced_Nit;
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

        }else if (!preg_match('/^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ. ]{3,40}$/', $this->Cli_RazonSocial)){

            self::$alertas['error-cliente']['razonsocial'][]="Solo debe contener letras y números, mayor a 3 caracteres";

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
        
        // Tipo Cliente
        if (!$this->Cli_TipoCliente){

            self::$alertas['error-cliente']['tipoCliente'][]="El campo Tipo Cliente es obligatorio";

        }else if (!preg_match('/^[CN]{1}$/', $this->Cli_TipoCliente)){

            self::$alertas['error-cliente']['tipoCliente'][]="Debe seleccionar una opción";

        }else{
            // Borramos los espacios de los lados
            $this->Cli_TipoCliente = trim($this->Cli_TipoCliente);
        }

        // Teléfono
        if (!$this->Cli_Tel){

            self::$alertas['error-cliente']['tel'][]="El campo Teléfono es obligatorio";

        }else if (!preg_match('/^[0-9]{10}$/', $this->Cli_Tel)){

            self::$alertas['error-cliente']['tel'][]="Solo debe contener números";

        }else{
            // Borramos los espacios de los lados
            $this->Cli_Tel = trim($this->Cli_Tel);
        }

        // Email
        if (!$this->Cli_Email){
    
            if (!preg_match('/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/', $this->Cli_Email)){
                
                self::$alertas['error-cliente']['email'][]="El email no es válido";
            }else{
                
                $this->Cli_Email = trim($this->Cli_Email);
            }
        }

        // Dirección
        if (!$this->Cli_Direccion){

            self::$alertas['error-cliente']['direccion'][]="El campo Dirección es obligatorio";

        }else if (!preg_match('/^[-0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ#º ]{6,50}$/', $this->Cli_Direccion)){

            self::$alertas['error-cliente']['direccion'][]="Dirección no válida";

        }else{
            // Borramos los espacios de los lados
            $this->Cli_Direccion = trim($this->Cli_Direccion);
        }

        // Ciudad
        if (!$this->Cli_FkCiud_Id){

            self::$alertas['error-cliente']['ciudad'][]="El campo Ciudad es obligatorio";

        }else if (!preg_match('/^[0-9]{1,4}$/', $this->Cli_FkCiud_Id)){

            self::$alertas['error-cliente']['ciudad'][]="Opción inválida";

        }else{
            // Borramos los espacios de los lados
            $this->Cli_FkCiud_Id = trim($this->Cli_FkCiud_Id);
        }
        
        return self::$alertas;
    }
}

?>