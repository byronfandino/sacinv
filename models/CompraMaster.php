<?php

namespace Model;

class CompraMaster extends ActiveRecord{

    protected static $tabla = 'tblcompra_master';
    protected static $columnasDB = ['CM_Id', 'CM_FkProvId', 'CM_FkMP_Id', 'CM_SoportePago', 'CM_NumFactura', 'CM_Fecha', 'CM_Subtotal', 'CM_IVA', 'CM_Descuento', 'CM_TotalFactura', 'CM_EstadoFactura', 'CM_Observaciones', 'CM_Status'];

    public $CM_Id;
    public $CM_FkProvId;
    public $CM_FkMP_Id;
    public $CM_SoportePago;
    public $CM_NumFactura;
    public $CM_Fecha;
    public $CM_Subtotal;
    public $CM_IVA;
    public $CM_Descuento;
    public $CM_TotalFactura;
    public $CM_EstadoFactura;
    public $CM_Observaciones;
    public $CM_Status;

    public function __construct($args = [])
    { 
        $this->CM_Id = $args['CM_Id'] ?? null;
        $this->CM_FkProvId = $args['CM_FkProvId'] ?? '';
        $this->CM_FkMP_Id = $args['CM_FkMP_Id'] ?? '';
        $this->CM_SoportePago = $args['CM_SoportePago'] ?? 'Sin Adjunto';
        $this->CM_NumFactura = $args['CM_NumFactura'] ?? '';
        $this->CM_Fecha = $args['CM_Fecha'] ?? '';
        $this->CM_Subtotal = $args['CM_Subtotal'] ?? '';
        $this->CM_IVA = $args['CM_IVA'] ?? '';
        $this->CM_Descuento = $args['CM_Descuento'] ?? '';
        $this->CM_TotalFactura = $args['CM_TotalFactura'] ?? '';
        $this->CM_EstadoFactura = $args['CM_EstadoFactura'] ?? '';
        $this->CM_Observaciones = $args['CM_Observaciones'] ?? '';
        $this->CM_Status = $args['CM_Status'] ?? 'E';
    }

    public function validar(){

        if(!$this->CM_FkProvId){
            self::$alertas['error-compraMaster']['CM_FkProvId'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9]{1,2}$/', $this->CM_FkProvId)){
            self::$alertas['error-compraMaster']['CM_FkProvId'][]="Debe seleccionar una opción válida o agregue un nuevo proveedor";
        }else{
            $this->CM_FkProvId=trim($this->CM_FkProvId);
        }

        if(!$this->CM_FkMP_Id){
            self::$alertas['error-compraMaster']['CM_FkMP_Id'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9]{1,2}$/', $this->CM_FkMP_Id)){
            self::$alertas['error-compraMaster']['CM_FkMP_Id'][]="Debe seleccionar una opción válida";
        }else{
            $this->CM_FkMP_Id=trim($this->CM_FkMP_Id);
        }

        if(!$this->CM_NumFactura){
            self::$alertas['error-compraMaster']['CM_NumFactura'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9A-Z]{4,20}$/', $this->CM_NumFactura)){
            self::$alertas['error-compraMaster']['CM_NumFactura'][]="Este campo solo puede contener números y/o letras";
        }else{
            $this->CM_NumFactura=trim($this->CM_NumFactura);
        }
        
        if(!$this->CM_Fecha){
            self::$alertas['error-compraMaster']['CM_Fecha'][]="Este campo es obligatorio";
        }else if(!preg_match('/^\d{4}([\-/.])(0?[1-9]|1[1-2])\1(3[01]|[12][0-9]|0?[1-9])$/', $this->CM_Fecha)){
            self::$alertas['error-compraMaster']['CM_Fecha'][]="No es un fecha válida";
        }else{
            $this->CM_Fecha=trim($this->CM_Fecha);
        }

        if(!$this->CM_Subtotal){
            self::$alertas['error-compraMaster']['CM_Subtotal'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9., ]{2,10}$/', $this->CM_Subtotal)){
            self::$alertas['error-compraMaster']['CM_Subtotal'][]="No es un valor válido";
        }else{
            $this->CM_Subtotal=trim($this->CM_Subtotal);
        }

        if(!$this->CM_IVA){
            self::$alertas['error-compraMaster']['CM_IVA'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9., ]{2,10}$/', $this->CM_IVA)){
            self::$alertas['error-compraMaster']['CM_IVA'][]="No es un valor válido";
        }else{
            $this->CM_IVA=trim($this->CM_IVA);
        }

        if(!$this->CM_Descuento){
            self::$alertas['error-compraMaster']['CM_Descuento'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9., ]{2,10}$/', $this->CM_Descuento)){
            self::$alertas['error-compraMaster']['CM_Descuento'][]="No es un valor válido";
        }else{
            $this->CM_Descuento=trim($this->CM_Descuento);
        }

        if(!$this->CM_TotalFactura){
            self::$alertas['error-compraMaster']['CM_TotalFactura'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9., ]{2,10}$/', $this->CM_TotalFactura)){
            self::$alertas['error-compraMaster']['CM_TotalFactura'][]="Este campo solo puede contener...";
        }else{
            $this->CM_TotalFactura=trim($this->CM_TotalFactura);
        }

        if(!$this->CM_EstadoFactura){
            self::$alertas['error-compraMaster']['CM_EstadoFactura'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[CS]{1}$/', $this->CM_EstadoFactura)){
            self::$alertas['error-compraMaster']['CM_EstadoFactura'][]="Debe seleccionar una opción válida";
        }else{
            $this->CM_EstadoFactura=trim($this->CM_EstadoFactura);
        }

        if(!$this->CM_Observaciones){
            self::$alertas['error-compraMaster']['CM_Observaciones'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[-0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ. ]{4,500}$/', $this->CM_Observaciones)){
            self::$alertas['error-compraMaster']['CM_Observaciones'][]="Este campo solo puede contener...";
        }else{
            $this->CM_Observaciones=trim($this->CM_Observaciones);
        }

        return self::$alertas;
    } 
}

?>