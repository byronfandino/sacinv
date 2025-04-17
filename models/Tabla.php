<?php

namespace Model;

class Tabla extends ActiveRecord{
    protected static $tabla='tabla';
    protected static $columnasDB=['id_tb','nombre_tb'];

    public $id_tb;
    public $nombre_tb;

    public function __construct($args=[]){
        
        $this->id_tb=$args['id_tb'] ?? null;
        $this->nombre_tb=$args['nombre_tb'] ?? '';

    }

    public function validar(){
        // 1. El usuario no digitó nada
        if (!$this->nombre_tb){
            self::$alertas['error']['nombre_tb'][]="El campo nombre es Obligatorio";

        }else if (!preg_match('/^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{2,100}$/', $this->nombre_tb)){
            self::$alertas['error']['nombre_tb'][]="Solo se aceptan letras y números";

        }else{
            $this->nombre_tb = trim($this->nombre_tb);
        }

        return self::$alertas;
    }
}

?>