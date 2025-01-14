<?php

namespace Model;

class Cliente extends ActiveRecord{
    protected static $tabla='cliente';
    protected static $columnasDB=['id_cliente','cedula_nit','nombre','telefono','direccion','fk_ciudad'];

    public $id_cliente;
    public $cedula_nit;
    public $nombre;
    public $telefono;
    public $direccion;
    public $fk_ciudad;

    public function __construct($args=[]){
        
        $this->id_cliente=$args['id_cliente'] ?? null;
        $this->cedula_nit=$args['cedula_nit'] ?? '';
        $this->nombre=$args['nombre'] ?? '';
        $this->telefono=$args['telefono'] ?? '';
        $this->direccion=$args['direccion'] ?? '';
        $this->fk_ciudad=$args['fk_ciudad'] ?? '';
        
    }

    public function validar(){
        // 1. El usuario no digitó nada
        if (!$this->cedula_nit){
            self::$alertas['error']['cedula_nit'][]="El campo Cédula o Nit es Obligatorio";
        }else{
            $this->cedula_nit = trim($this->cedula_nit);
        }
        if (!$this->nombre){
            self::$alertas['error']['nombre'][]="El campo Nombre es Obligatorio";
        }else{
            $this->nombre = trim($this->nombre);
        }
        if (!$this->telefono){
            self::$alertas['error']['telefono'][]="El campo telefono es Obligatorio";
        }else{
            $this->telefono = trim($this->telefono);
        }
        if (!$this->direccion){
            self::$alertas['error']['direccion'][]="El campo direccion es Obligatorio";
        }else{
            $this->direccion = trim($this->direccion);
        }
        if (!$this->fk_ciudad){
            self::$alertas['error']['fk_ciudad'][]="El campo Ciudad es Obligatorio";
        }else{
            $this->fk_ciudad = trim($this->fk_ciudad);
        }

        return self::$alertas;
    }
}

?>