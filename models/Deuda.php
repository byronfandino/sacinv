<?php

namespace Model;

class Deuda extends ActiveRecord{
    protected static $tabla = 'deuda';
    protected static $columnasDB = ['id_deuda', 'fk_cliente'];
    
    public $id_deuda;
    public $fk_cliente; 
    public $saldo;

    public function __construct($args = [])
    {
        $this->id_deuda = $args['id_deuda'] ?? null;
        $this->fk_cliente = $args['fk_cliente'] ?? '';
        $this->saldo = $args['saldo'] ?? 0;
    }

    public function validar(){

        if(!$this->fk_cliente){
            self::$alertas['error']['fk_cliente'][] = "Debe seleccionar un cliente";
        }else if (!preg_match('/^[0-9]{1,10}$/', $this->fk_cliente)){
            self::$alertas['error']['fk_cliente'][] = "No seleccionó un cliente válido";
        }else{
            $this->fk_cliente = trim($this->fk_cliente);
        }

        return self::$alertas;
    }

    public function setIdDeuda($id){
        $this->id_deuda = $id;
    }
}

?>