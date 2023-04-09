<?php

namespace Model;

class Tabla extends ActiveRecord{
    
    protected static $tabla = 'tbltabla';
    protected static $columnasDB = ['Tb_Id', 'Tb_Nombre', 'Tb_Status'];

    public $Tb_Id;
    public $Tb_Nombre;    
    public $Tb_Status;    

    public function __construct($args = [])
    {
        $this->Tb_Id = $args['Tb_Id'] ?? null;
        $this->Tb_Nombre = $args['Tb_Nombre'] ?? '';
        $this->Tb_Status = $args['Tb_Status'] ?? '';
    }

    public function validar(){
        // 1. El usuario no digitó nada
        if (!$this->Tb_Nombre){
            self::$alertas['error-tabla']['nombre'][]="El campo Nombre es Obligatorio";
        }else{
            // Convertimos la primera letra en mayúsculas y borramos los espacios de los lados
            $this->Tb_Nombre = ucfirst(strtolower(trim($this->Tb_Nombre)));
        }

        // 2. Expresiones regulares
        if (!preg_match('/^[A-ZÑa-züñáéíóúÁÉÍÓÚÜ° 0-9_]{4,50}$/', $this->Tb_Nombre)){
            self::$alertas['error-tabla']['nombre'][]="Solo debe contener letras, números o guión bajo ( _ ) mayor a 4 caracteres";
        }
        return self::$alertas;
    }
}

?>