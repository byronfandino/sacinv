<?php

namespace Model;

class ProductoOferta extends ActiveRecord{
    
    protected static $tabla = 'tblproducto_oferta';
    protected static $columnasDB = ['PO_Id', 'PO_Cant', 'PO_ValorOferta', 'PO_FkProducto_Id','PO_Status'];

    public $PO_Id;
    public $PO_Cant;
    public $PO_ValorOferta;
    public $PO_FkProducto_Id;
    public $PO_Status;

    public function __construct($args = [])
    {
        $this->PO_Id=$args['PO_Id'] ?? null;
        $this->PO_FkProducto_Id=$args['PO_FkProducto_Id'] ?? '';
        $this->PO_Cant=$args['PO_Cant'] ?? '';
        $this->PO_ValorOferta=$args['PO_ValorOferta'] ?? '';
        $this->PO_Status=$args['PO_Status'] ?? 'E';
    }

    public function validar(){
    
        // Si se digita un campo pero el otro no, es obligatorio que ambos campos se digiten, o que ninguno de los 2 se digite por el usuario
        if($this->PO_Cant && !$this->PO_ValorOferta){
            self::$alertas['error-producto']['oferta-valor'][]="Si digitó una Cantidad x Oferta, entonces debe digitar un Valor x Oferta"; 
            
        }else if(!$this->PO_Cant && $this->PO_ValorOferta){
            self::$alertas['error-producto']['oferta-cantidad'][]="Si digitó una Valor x Oferta, entonces debe digitar la Cantidad x Oferta"; 

        }else if ($this->PO_Cant && $this->PO_ValorOferta){

            if(!is_numeric($this->PO_Cant)){
                self::$alertas['error-producto']['oferta-cantidad'][]="Este campo solo puede contener números"; 
            }else if($this->PO_Cant < 0){
                self::$alertas['error-producto']['oferta-cantidad'][]="Este campo no puede tener número negativos"; 
            }
            
            if (!is_numeric($this->PO_ValorOferta)){
                self::$alertas['error-producto']['oferta-valor'][]="Este campo solo puede contener números"; 
            }else if($this->PO_ValorOferta < 0){
                self::$alertas['error-producto']['oferta-valor'][]="Este campo no puede tener número negativos"; 
            }


        }

        return self::$alertas;
    }
    // agregar llave foránea
    public function setFkId($id){
        $this->PO_FkProducto_Id = $id;
    }
}

?>