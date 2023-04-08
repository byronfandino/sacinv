<?php

namespace Model;

class VentaDetalle extends ActiveRecord{
    protected static $tabla='tblventa_detalle';
    protected static $columnasDB = [ 'VD_Id', 'VD_FkVM_Id', 'VD_FkProd_Id', 'VD_FkUs_Id', 'VD_Fecha', 'VD_Hora', 'VD_Cantidad', 'VD_ValorUnitario', 'VD_Descuento', 'VD_ValorTotal', 'VD_Status'];

    public $VD_Id;
    public $VD_FkVM_Id;
    public $VD_FkProd_Id;
    public $VD_FkUs_Id;
    public $VD_Fecha;
    public $VD_Hora;
    public $VD_Cantidad;
    public $VD_ValorUnitario;
    public $VD_Descuento;
    public $VD_ValorTotal;
    public $VD_Status;

    public function __construct($args = [])
    {
        $this->VD_Id = $args['VD_Id'] ?? null;
        $this->VD_FkVM_Id = $args['VD_FkVM_Id'] ?? '';
        $this->VD_FkProd_Id = $args['VD_FkProd_Id'] ?? '';
        $this->VD_FkUs_Id = $args['VD_FkUs_Id'] ?? '';
        $this->VD_Fecha = $args['VD_Fecha'] ?? '';
        $this->VD_Hora = $args['VD_Hora'] ?? '';
        $this->VD_Cantidad = $args['VD_Cantidad'] ?? '';
        $this->VD_ValorUnitario = $args['VD_ValorUnitario'] ?? '';
        $this->VD_Descuento = $args['VD_Descuento'] ?? '';
        $this->VD_ValorTotal = $args['VD_ValorTotal'] ?? '';
        $this->VD_Status = $args['VD_Status'] ?? 'E';
    }

}

?>