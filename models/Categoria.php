<?php

namespace Model;

class Categoria extends ActiveRecord{

    protected static $tabla = 'tblcategoria';
    protected static $columnasDB = ['Ctg_Id', 'Ctg_Descripcion', 'Ctg_Status'];

    public $Ctg_Id;    
    public $Ctg_Descripcion;    
    public $Ctg_Status;

    public function __construct($args = [])
    {
        $this->Ctg_Id=$args['Ctg_Id'] ?? null;            
        $this->Ctg_Descripcion=$args['Ctg_Descripcion'] ?? '';
        $this->Ctg_Status=$args['Ctg_Status'] ?? 'E';        
    }

    public function validar(){
        // 1. El usuario no digitó nada
        if (!$this->Ctg_Descripcion){
            self::$alertas['error-categoria']['nombre'][]="El campo Nombre de la Categoria es Obligatorio";
        }else{
            // Convertimos la primera letra en mayúsculas y borramos los espacios de los lados
            $this->Ctg_Descripcion = trim($this->Ctg_Descripcion);
            // $this->Ctg_Descripcion = ucfirst(strtolower(trim($this->Ctg_Descripcion)));
        }

        // 2. Expresiones regulares
        if (!preg_match('/^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ° ]{2,30}$/', $this->Ctg_Descripcion)){
            self::$alertas['error-categoria']['nombre'][]="Solo debe contener letras y números, mayor a 2 caracteres";
        }
        return self::$alertas;
    }
}

?>