<?php

namespace Model;

class ProductoCodigo extends ActiveRecord{
    protected static $tabla = 'tblproducto_codigo';
    protected static $columnasDB = ['Cod_Id','Cod_Barras','Cod_Manual','Cod_FkProd_Id'];

    public $Cod_Id;
    public $Cod_Barras;
    public $Cod_Manual;
    public $Cod_FkProd_Id;

    public function __construct($args = [])
    {
        $this->Cod_Id=$args['Cod_Id'] ?? null;
        $this->Cod_Barras=$args['Cod_Barras'] ?? '';
        $this->Cod_Manual=$args['Cod_Manual'] ?? '';
        $this->Cod_FkProd_Id=$args['Cod_FkProd_Id'] ?? 'E';
    }

    public function validar(){
        
        // Si se digita un campo pero el otro no, es obligatorio que ambos campos se digiten, o que ninguno de los 2 se digite por el usuario
        // if(!$this->Cod_Barras || !preg_match('/^[0-9A-Z]{4,20}$/', $this->Cod_Barras)){
        //     self::$alertas['error-producto']['codigo-barras'][]="Este campo es alfanumérico, mayor a 4 caracteres y no puede contener espacios ni caracteres especiales";     
        // }
        
        if(!$this->Cod_Manual || !preg_match('/^[0-9A-Z]{4,20}$/', $this->Cod_Manual)){
            self::$alertas['error-producto']['codigo-manual'][]="Este campo es alfanumérico, mayor a 4 caracteres y no puede contener espacios ni caracteres especiales";     
        }        

        return self::$alertas;
    }

    // agregar llave foránea
    public function setFkId($id){
        $this->Cod_FkProd_Id = $id;
    }
}

?>