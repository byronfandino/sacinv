<?php

namespace Model;

class Proveedor extends ActiveRecord{

    protected static $tabla = 'tblproveedor';
    protected static $columnasDB = ['Prov_Id', 'Prov_Nit', 'Prov_RazonSocial', 'Prov_Tel', 'Prov_Email', 'Prov_Direccion', 'Prov_FkCiud_Id', 'Prov_Status'];

    public $Prov_Id;
    public $Prov_Nit;
    public $Prov_RazonSocial;
    public $Prov_Tel;
    public $Prov_Email;
    public $Prov_Direccion;
    public $Prov_FkCiud_Id;
    public $Prov_Status;

    public function __construct($args = [])
    {
        $this->Prov_Id = $args['$Prov_Id'] ?? null;
        $this->Prov_Nit = $args['$Prov_Nit'] ?? '';
        $this->Prov_RazonSocial = $args['$Prov_RazonSocial'] ?? '';
        $this->Prov_Tel = $args['$Prov_Tel'] ?? '';
        $this->Prov_Email = $args['$Prov_Email'] ?? '';
        $this->Prov_Direccion = $args['$Prov_Direccion'] ?? '';
        $this->Prov_FkCiud_Id = $args['$Prov_FkCiud_Id'] ?? '';
        $this->Prov_Status = $args['$Prov_Status'] ?? 'E';
    }

    public function validar(){
        // 1. El usuario no digitó nada
        if (!$this->Prov_RazonSocial){

            self::$alertas['error-proveedor']['razonsocial'][]="El campo Nombre - Razon Social es obligatorio";

        }else if (!preg_match('/^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ. ]{2,40}$/', $this->Prov_RazonSocial)){

            self::$alertas['error-proveedor']['razonsocial'][]="Solo debe contener letras y números, mayor a 3 caracteres";
            
        }else{
            // Borramos los espacios de los lados
            $this->Prov_RazonSocial = trim($this->Prov_RazonSocial);
        }

        // Cédula
        if (!$this->Prov_Nit){

            self::$alertas['error-proveedor']['nit'][]="El campo Nit es obligatorio";

        }else if (!preg_match('/^[0-9-]{4,15}$/', $this->Prov_Nit)){

            self::$alertas['error-proveedor']['nit'][]="Solo debe contener números, mayor a 4 caracteres";

        }else{
            // Borramos los espacios de los lados
            $this->Prov_Nit = trim($this->Prov_Nit);
        }

        // Teléfono
        if (!$this->Prov_Tel){

            self::$alertas['error-proveedor']['tel'][]="El campo Teléfono es obligatorio";

        }else if (!preg_match('/^[0-9]{10}$/', $this->Prov_Tel)){

            self::$alertas['error-proveedor']['tel'][]="Solo debe contener números";

        }else{
            // Borramos los espacios de los lados
            $this->Prov_Tel = trim($this->Prov_Tel);
        }

        // Email
        if (!$this->Prov_Email){
    
            if (!preg_match('/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/', $this->Prov_Email)){
                
                self::$alertas['error-proveedor']['email'][]="El email no es válido";
            }else{
                
                $this->Prov_Email = trim($this->Prov_Email);
            }
        }

        // Dirección
        if (!$this->Prov_Direccion){

            self::$alertas['error-proveedor']['direccion'][]="El campo Dirección es obligatorio";

        }else if (!preg_match('/^[-0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ#º ]{6,50}$/', $this->Prov_Direccion)){

            self::$alertas['error-proveedor']['direccion'][]="Dirección no válida";

        }else{
            
            // Borramos los espacios de los lados
            $this->Prov_Direccion = trim($this->Prov_Direccion);
        }

        // Ciudad
        if (!$this->Prov_FkCiud_Id){

            self::$alertas['error-proveedor']['ciudad'][]="El campo Ciudad es obligatorio";

        }else if (!preg_match('/^[0-9]{1,4}$/', $this->Prov_FkCiud_Id)){

            self::$alertas['error-proveedor']['ciudad'][]="Opción inválida";

        }else{
            // Borramos los espacios de los lados
            $this->Prov_FkCiud_Id = trim($this->Prov_FkCiud_Id);
        }
        
        return self::$alertas;
    }
}

?>