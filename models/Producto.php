<?php

namespace Model;

class Producto extends ActiveRecord{

    protected static $tabla = 'tblproducto';
    protected static $columnasDB = ['Prod_Id', 'Prod_Descripcion', 'Prod_Observ', 'Prod_ValorVenta', 'Prod_ValorDesc', 'Prod_CantMinStock', 'Prod_FkMc_Id', 'Prod_FkCtg_Id', 'Prod_Status'];

    public $Prod_Id;
    public $Prod_Descripcion;
    public $Prod_Observ;
    public $Prod_ValorVenta;
    public $Prod_ValorDesc;
    public $Prod_CantMinStock;
    public $Prod_FkMc_Id;
    public $Prod_FkCtg_Id;
    public $Prod_Status;

    public function __construct($args = [])
    { 
        $this->Prod_Id = $args['Prod_Id'] ?? null;            
        $this->Prod_Descripcion = $args['Prod_Descripcion'] ?? '';        
        $this->Prod_Observ = $args['Prod_Observ'] ?? ''; 
        $this->Prod_ValorVenta = $args['Prod_ValorVenta'] ?? '';        
        $this->Prod_ValorDesc = $args['Prod_ValorDesc'] ?? '';        
        $this->Prod_CantMinStock = $args['Prod_CantMinStock'] ?? '';        
        $this->Prod_FkMc_Id = $args['Prod_FkMc_Id'] ?? '';        
        $this->Prod_FkCtg_Id = $args['Prod_FkCtg_Id'] ?? '';        
        $this->Prod_Status = $args['Prod_Status'] ?? 'E';
        
    }

    public function validar(){

        if(!$this->Prod_Descripcion){
            
            self::$alertas['error-producto']['descripcion'][] = "Este campo es obligatorio";
            
        }else if(!preg_match('/^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{4,100}$/', $this->Prod_Descripcion)){
            
            self::$alertas['error-producto']['descripcion'][] = "Este campo es alfanumérico, no se permite caractéres especiales, y debe tener más de 4 caracteres";
        
        }else{

            $this->Prod_Descripcion = trim($this->Prod_Descripcion);
        }        

        if(!$this->Prod_ValorVenta || $this->Prod_ValorVenta=="0"){
            self::$alertas['error-producto']['valor-venta'][] = "Este campo es obligatorio, y su valor debe ser diferente de 0";

        }else if(!is_numeric($this->Prod_ValorVenta)){
            self::$alertas['error-producto']['valor-venta'][] = "Este campo solo puede contener números, no mayor a 10 caracteres";
        }   

        if(!$this->Prod_ValorDesc ){

            if ($this->Prod_ValorDesc !== ""){
                if (!is_numeric($this->Prod_ValorDesc)){                
                    self::$alertas['error-producto']['valor-descuento'][] = "Este campo solo puede contener números, no mayor a 6 cifras";       
                }
            }else{
                $this->Prod_ValorDesc = 0;
            }
        }

        if(!$this->Prod_CantMinStock || $this->Prod_CantMinStock=="0"){

            self::$alertas['error-producto']['cantidad-stock'][] = "Este campo es obligatorio, y debe ser diferente de 0";
            
        }else if(!is_numeric($this->Prod_CantMinStock)){
            
            self::$alertas['error-producto']['cantidad-stock'][] = "Este campo solo puede contener números, no mayor a 3 cifras";
        }

        if(!$this->Prod_FkMc_Id){
            self::$alertas['error-producto']['marca'][] = "Este campo es obligatorio";
        }else if(!preg_match('/^[0-9]{1,3}$/', $this->Prod_FkMc_Id)){
             self::$alertas['error-producto']['marca'][] = "Este campo no contiene un valor válido";
        }

        if(!$this->Prod_FkCtg_Id){
            self::$alertas['error-producto']['categoria'][] = "Este campo es obligatorio";
        }else if(!preg_match('/^[0-9]{1,3}$/', $this->Prod_FkCtg_Id)){
             self::$alertas['error-producto']['categoria'][] = "Este campo no contiene un valor válido";
        }

        if($this->Prod_Observ == ""){
            $this->Prod_Observ="Ninguna";
        }

        return self::$alertas;
    } 
}


?>