<?php

namespace Model;

class Marca extends ActiveRecord{
    
    protected static $tabla = 'tblmarca';
    protected static $columnasDB = ['Mc_Id', 'Mc_Descripcion', 'Mc_Status'];

    public $Mc_Id;
    public $Mc_Descripcion;
    public $Mc_Status;
    
    public function __construct($args = [])
    {
        $this->Mc_Id=$args['Mc_Id'] ?? null;                
        $this->Mc_Descripcion=$args['Mc_Descripcion'] ?? '';            
        $this->Mc_Status=$args['Mc_Status'] ?? 'E';                
    }

    public function validar(){
        // 1. El usuario no digitó nada
        if (!$this->Mc_Descripcion){
            self::$alertas['error-marca']['nombre'][]="El campo Nombre de la Marca es Obligatorio";
        }else{
            // Convertimos la primera letra en mayúsculas y borramos los espacios de los lados
            $this->Mc_Descripcion = trim($this->Mc_Descripcion);
        }

        // 2. Expresiones regulares
        if (!preg_match('/^[A-ZÑa-züñáéíóúÁÉÍÓÚÜ° 0-9]{2,30}$/', $this->Mc_Descripcion)){
            self::$alertas['error-marca']['nombre'][]="Solo debe contener letras y/o números, mayor a 2 caracteres";
        }
        return self::$alertas;
    }
}

?>