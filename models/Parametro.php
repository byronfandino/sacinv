<?php

namespace Model;

class Parametro extends ActiveRecord{
    protected static $tabla = 'tblparametros';
    protected static $columnasDB = ['Pm_Id','Pm_Nit','Pm_RazonSocial','Pm_Direccion',
    'Pm_Tel','Pm_Whatsapp','Pm_Email','Pm_Slogan','Pm_Logo','Pm_TipoInv'];

    public $Pm_Id;
    public $Pm_Nit;
    public $Pm_RazonSocial;
    public $Pm_Direccion;
    public $Pm_Tel;
    public $Pm_Whatsapp;
    public $Pm_Email;
    public $Pm_Slogan;
    public $Pm_Logo;
    public $Pm_TipoInv;

    public function __construct($args = [])
    {
        $this->Pm_Id=$args['Pm_Id'] ?? null;
        $this->Pm_Nit=$args['Pm_Nit'] ?? '';
        $this->Pm_RazonSocial=$args['Pm_RazonSocial'] ?? '';
        $this->Pm_Direccion=$args['Pm_Direccion'] ?? '';
        $this->Pm_Tel=$args['Pm_Tel'] ?? '';
        $this->Pm_Whatsapp=$args['Pm_Whatsapp'] ?? '';
        $this->Pm_Email=$args['Pm_Email'] ?? '';
        $this->Pm_Slogan=$args['Pm_Slogan'] ?? '';
        $this->Pm_Logo=$args['Pm_Logo'] ?? null;
        $this->Pm_TipoInv=$args['Pm_TipoInv'] ?? '';
    }

    public function validar(){
        // Los campos Whatsapp, Leyenda, logo no son obligatorios
        if(!$this->Pm_Nit){
            self::$alertas['error-parametro']['nit'][] = "El campo Nit es obligatorio";            
        }

        if (!preg_match('/^[0-9-]{4,12}$/', $this->Pm_Nit)){
            self::$alertas['error-parametro']['nit'][]="Solo debe contener números o guión medio ( - ) mayor a 4 caracteres";
        }else{
            $this->Pm_Nit = trim($this->Pm_Nit);
        }

        if(!$this->Pm_RazonSocial){
            self::$alertas['error-parametro']['razonsocial'][] = "El campo Razón Social es obligatorio";            
        }

        if (!preg_match('/^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ\' ]{4,100}$/', $this->Pm_RazonSocial)){
            self::$alertas['error-parametro']['razonsocial'][]="Este campo puede contener letras, números y espacios, sin caracteres especiales, mayor a 3 caracteres";
        }else{
            $this->Pm_RazonSocial = trim($this->Pm_RazonSocial);
        }
        
        if(!$this->Pm_Direccion){
            self::$alertas['error-parametro']['direccion'][] = "El campo Dirección es obligatorio";            
        }
        
        if (!preg_match('/^[#.A-Za-z0-9- ]{10,30}$/', $this->Pm_Direccion)){
            self::$alertas['error-parametro']['direccion'][]="Este campo solo puede contener letras, espacios y caracteres como (#-.), mayor a 10 caracteres";
        }else{
            $this->Pm_Direccion = trim($this->Pm_Direccion);
        }
        
        if(!$this->Pm_Tel){
            self::$alertas['error-parametro']['telefono'][] = "El campo Teléfono es obligatorio";            
        }

        if (!preg_match('/^[+0-9]{10,13}$/', $this->Pm_Tel)){
            self::$alertas['error-parametro']['telefono'][]="Este campo debe contener solo números, igual a 10 caracteres";
        }else{
            $this->Pm_Tel = trim($this->Pm_Tel);
        }

        if (!preg_match('/^[+\/NA0-9]{3,13}$/', $this->Pm_Whatsapp)){
            self::$alertas['error-parametro']['whatsapp'][]="Este campo debe contener solo números, igual a 10 caracteres";
        }else{
            $this->Pm_Whatsapp = trim($this->Pm_Whatsapp);
        }
        
        if(!$this->Pm_Email){
            self::$alertas['error-parametro']['email'][] = "El campo Email es obligatorio";            
        }
    
        if (!preg_match('/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/', $this->Pm_Email)){
            self::$alertas['error-parametro']['email'][]="No cumple con los requisitos mínimos de un correo electrónico";
        }else{
            $this->Pm_Email = trim($this->Pm_Email);
        }

        if (!preg_match('/^[A-ZÑa-züñáéíóúÁÉÍÓÚÜ°\/ 0-9]{3,100}$/', $this->Pm_Slogan)){
            self::$alertas['error-parametro']['slogan'][]="Este campo puede contener letras, números y espacios, sin caracteres especiales, mayor a 8 caracteres";
        }else{
            $this->Pm_Slogan = trim($this->Pm_Slogan);
        }

        if(!$this->Pm_TipoInv){
            self::$alertas['error-parametro']['tipo-inventario'][] = "El campo Tipo de Inventario es obligatorio";
        }

        if (!preg_match('/^[FLP]{1}$/', $this->Pm_TipoInv)){
            self::$alertas['error-parametro']['tipo-inventario'][]="El Tipo de Inventario no corresponde a la registrada en el sistema";
        }else{
            $this->Pm_TipoInv = trim($this->Pm_TipoInv);
        }

        if (!preg_match('/^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ\/ ]{3,200}$/', $this->Pm_Slogan)){
            self::$alertas['error-parametro']['tipo-inventario'][]="El Tipo de Inventario no corresponde a la registrada en el sistema";
        }else{
            $this->Pm_Slogan = trim($this->Pm_Slogan);
        }

        return self::$alertas;
    }

    public function verificarImagen($imagen){
        if($imagen['size'] > 500000){
            self::$alertas['error-parametro']['imagen'][]="La imagen es muy pesada, debe ser inferior a 500 KB";
        }

        if(!($imagen['type'] == "image/png" || $imagen['type'] == "image/jpg" || $imagen['type'] == "image/jpeg")){
            self::$alertas['error-parametro']['imagen'][]="Este tipo de archivo no está permitido";
        }
        return self::$alertas;

    }

    public function setImagen($nombreImagen){
        if($nombreImagen){
            $this->Pm_Logo = $nombreImagen;
        }
    }
}

?>