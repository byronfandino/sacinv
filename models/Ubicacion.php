<?php

namespace Model;

class Ubicacion extends ActiveRecord{

    protected static $tabla = 'tblubicacion';
    protected static $columnasDB = ['Ubicacion_Id', 'Ubicacion_Nombre', 'Ubicacion_Status'];

    public $Ubicacion_Id;    
    public $Ubicacion_Nombre;
    public $Ubicacion_Status;

    public function __construct($args = [])
    {
        $this->Ubicacion_Id = $args['Ubicacion_Id'] ?? null;                
        $this->Ubicacion_Nombre = $args['Ubicacion_Nombre'] ?? '';            
        $this->Ubicacion_Status = $args['Ubicacion_Status'] ?? 'E';                
    }

    public function validar(){
        // 1. El usuario no digitó nada
        if (!$this->Ubicacion_Nombre){
            self::$alertas['error-ubicacion']['nombre'][]="El campo Nombre es Obligatorio";
        }else{
            // Convertimos la primera letra en mayúsculas y borramos los espacios de los lados
            $this->Ubicacion_Nombre = ucfirst(strtolower(trim($this->Ubicacion_Nombre)));
        }

        // 2. Expresiones regulares
        if (!preg_match('/^[A-ZÑa-züñáéíóúÁÉÍÓÚÜ° 0-9]{4,50}$/', $this->Ubicacion_Nombre)){
            self::$alertas['error-ubicacion']['nombre'][]="Solo debe contener letras y/o números, mayor a 4 caracteres";
        }
        return self::$alertas;
    }
}


?>