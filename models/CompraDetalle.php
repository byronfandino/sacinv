<?php

namespace Model;

class CompraDetalle extends ActiveRecord{

    protected static $tabla = 'tblcompra_detalle';
    protected static $columnasDB = ['CD_Id','CD_FkCM_Id','CD_FkProd_Id','CD_Cantidad','CD_ValorUnit','CD_XjeDesc','CD_ValorDesc','CD_Total','CD_XjeVenta', 'CD_ValorVenta','CD_FechaVenc','CD_Status'];

    public $CD_Id;
    public $CD_FkCM_Id;
    public $CD_FkProd_Id;
    public $CD_Cantidad;
    public $CD_ValorUnit;
    public $CD_XjeDesc;
    public $CD_ValorDesc;
    public $CD_Total;
    public $CD_XjeVenta;
    public $CD_ValorVenta;
    public $CD_FechaVenc;
    public $CD_Status;
    

    public function __construct($args = [])
    { 
        $this->CD_Id = $args['CD_Id'] ?? null;
        $this->CD_FkCM_Id = $args['CD_FkCM_Id'] ?? '';
        $this->CD_FkProd_Id = $args['CD_FkProd_Id'] ?? '';
        $this->CD_Cantidad = $args['CD_Cantidad'] ?? 'Sin Adjunto';
        $this->CD_ValorUnit = $args['CD_ValorUnit'] ?? '';
        $this->CD_XjeDesc = $args['CD_XjeDesc'] ?? '';
        $this->CD_ValorDesc = $args['CD_ValorDesc'] ?? '';
        $this->CD_Total = $args['CD_Total'] ?? '';
        $this->CD_XjeVenta = $args['CD_XjeVenta'] ?? '';
        $this->CD_ValorVenta = $args['CD_ValorVenta'] ?? '';
        $this->CD_FechaVenc = $args['CD_FechaVenc'] ?? '';
        $this->CD_Status = $args['CD_Status'] ?? 'E';

    }

    public function validar(){

        if(!$this->CD_FkCM_Id){
            self::$alertas['error-compraDetalle']['CD_FkCM_Id'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9]{1,10}$/', $this->CD_FkCM_Id)){
            self::$alertas['error-compraDetalle']['CD_FkCM_Id'][]="Debe seleccionar una opción válida";
        }else{
            $this->CD_FkCM_Id=trim($this->CD_FkCM_Id);
        }

        if(!$this->CD_FkProd_Id){
            self::$alertas['error-compraDetalle']['CD_FkProd_Id'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9]{1,5}$/', $this->CD_FkProd_Id)){
            self::$alertas['error-compraDetalle']['CD_FkProd_Id'][]="No es un producto válido";
        }else{
            $this->CD_FkProd_Id=trim($this->CD_FkProd_Id);
        }

        if(!$this->CD_Cantidad){
            self::$alertas['error-compraDetalle']['CD_Cantidad'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9]{1,4}$/', $this->CD_Cantidad)){
            self::$alertas['error-compraDetalle']['CD_Cantidad'][]="Este campo solo puede contener números";
        }else{
            $this->CD_Cantidad=trim($this->CD_Cantidad);
        }

        if(!$this->CD_ValorUnit){
            self::$alertas['error-compraDetalle']['CD_ValorUnit'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9.,]{1,10}$/', $this->CD_ValorUnit)){
            self::$alertas['error-compraDetalle']['CD_ValorUnit'][]="Este campo solo puede contener números";
        }else{
            $this->CD_ValorUnit=trim($this->CD_ValorUnit);
        }

        if(!$this->CD_XjeDesc){
            self::$alertas['error-compraDetalle']['CD_XjeDesc'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9.,]{1,4}$/', $this->CD_XjeDesc)){
            self::$alertas['error-compraDetalle']['CD_XjeDesc'][]="Este campo solo puede contener números";
        }else{
            $this->CD_XjeDesc=trim($this->CD_XjeDesc);
        }

        if(!$this->CD_ValorDesc){
            self::$alertas['error-compraDetalle']['CD_ValorDesc'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9.,]{1,10}$/', $this->CD_ValorDesc)){
            self::$alertas['error-compraDetalle']['CD_ValorDesc'][]="Este campo solo puede contener números";
        }else{
            $this->CD_ValorDesc=trim($this->CD_ValorDesc);
        }

        if(!$this->CD_ValorVenta){
            self::$alertas['error-compraDetalle']['CD_ValorVenta'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9.,]{1,10}$/', $this->CD_ValorVenta)){
            self::$alertas['error-compraDetalle']['CD_ValorVenta'][]="Este campo solo puede contener números";
        }else{
            $this->CD_ValorVenta=trim($this->CD_ValorVenta);
        }

        if(!$this->CD_Total){
            self::$alertas['error-compraDetalle']['CD_Total'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9.,]{1,10}$/', $this->CD_Total)){
            self::$alertas['error-compraDetalle']['CD_Total'][]="Este campo solo puede contener números";
        }else{
            $this->CD_Total=trim($this->CD_Total);
        }

        if(!$this->CD_XjeVenta){
            self::$alertas['error-compraDetalle']['CD_XjeVenta'][]="Este campo es obligatorio";
        }else if(!preg_match('/^[0-9.,]{1,4}$/', $this->CD_XjeVenta)){
            self::$alertas['error-compraDetalle']['CD_XjeVenta'][]="Este campo solo puede contener números";
        }else{
            $this->CD_XjeVenta=trim($this->CD_XjeVenta);
        }

        if(!$this->CD_FechaVenc){
            self::$alertas['error-compraDetalle']['CD_FechaVenc'][]="Este campo es obligatorio";
        }else if(!preg_match('/^\d{4}([\-/.])(0?[1-9]|1[1-2])\1(3[01]|[12][0-9]|0?[1-9])$/', $this->CD_FechaVenc)){
            self::$alertas['error-compraDetalle']['CD_FechaVenc'][]="Fecha No válida";
        }else{
            $this->CD_FechaVenc=trim($this->CD_FechaVenc);
        }

        return self::$alertas;
    } 
}

?>