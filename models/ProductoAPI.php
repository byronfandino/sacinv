<?php

namespace Model;

class ProductoAPI extends ActiveRecord{

    // protected static $tabla = 'tblproducto';
    protected static $columnasDB = ['Prod_Id', 'Cod_Manual', 'Cod_Barras', 'Prod_Descripcion', 
    'Ctg_Descripcion', 'Mc_Descripcion', 'Prod_ValorVenta', 'Prod_ValorDesc', 
    'Prod_CantMinStock', 'PO_Cant', 'PO_ValorOferta', 'Prod_Status'];
    
    public $Prod_Id;
    public $Cod_Manual;
    public $Cod_Barras;
    public $Prod_Descripcion;
    public $Ctg_Descripcion;
    public $Mc_Descripcion;
    public $Prod_ValorVenta;
    public $Prod_ValorDesc;
    public $Prod_CantMinStock;
    public $PO_Cant;
    public $PO_ValorOferta;
    public $Prod_Status;

    public function __construct($args = [])
    { 
        $this->Prod_Id = $args['Prod_Id'] ?? null;
        $this->Cod_Manual = $args['Cod_Manual'] ?? '';
        $this->Cod_Barras = $args['Cod_Barras'] ?? '';
        $this->Prod_Descripcion = $args['Prod_Descripcion'] ?? '';
        $this->Ctg_Descripcion = $args['Ctg_Descripcion'] ?? '';
        $this->Mc_Descripcion = $args['Mc_Descripcion'] ?? '';
        $this->Prod_ValorVenta = $args['Prod_ValorVenta'] ?? '';
        $this->Prod_ValorDesc = $args['Prod_ValorDesc'] ?? '';
        $this->Prod_CantMinStock = $args['Prod_CantMinStock'] ?? '';
        $this->PO_Cant = $args['PO_Cant'] ?? '';
        $this->PO_ValorOferta = $args['PO_ValorOferta'] ?? '';
        $this->Prod_Status = $args['Prod_Status'] ?? '';
    }
}


?>