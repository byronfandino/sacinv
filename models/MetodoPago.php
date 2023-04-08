<?php

namespace Model;

class MetodoPago extends ActiveRecord{
    protected static $tabla = 'tblmetodo_pago';
    protected static $columnasDB = ['MP_Id', 'MP_Nombre', 'MP_Status'];

    public $MP_Id;
    public $MP_Nombre;    
    public $MP_Status;

    public function __construct($args = [])
    {
       $this->MP_Id = $args['MP_Id'] ?? null;     
       $this->MP_Nombre = $args['MP_Nombre'] ?? ''; 
       $this->MP_Status = $args['MP_Status'] ?? 'E'; 
    }

    public function validar(){
        // 1. El usuario no digitó nada
        if (!$this->MP_Nombre){
            self::$alertas['error-metodoPago']['nombre'][]="El campo Nombre es Obligatorio";
        }else{
            // Convertimos la primera letra en mayúsculas y borramos los espacios de los lados
            $this->MP_Nombre = ucfirst(strtolower(trim($this->MP_Nombre)));
        }

        // 2. Expresiones regulares
        if (!preg_match('/^[A-ZÑa-züñáéíóúÁÉÍÓÚÜ° ]{4,50}$/', $this->MP_Nombre)){
            self::$alertas['error-metodoPago']['nombre'][]="Solo debe contener letras , mayor a 4 caracteres";
        }
        return self::$alertas;
    }
}
?>