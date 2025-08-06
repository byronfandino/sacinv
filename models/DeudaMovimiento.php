<?php
    namespace Model;

    class DeudaMovimiento extends ActiveRecord{
        protected static $tabla = 'deuda_movimiento';
        protected static $columnasDB = ['id_mov', 'fk_deuda', 'tipo_mov', 'descripcion', 'cant', 'valor_unit', 'valor_total', 'saldo', 'fecha', 'hora'];
        
        public $id_mov;
        public $fk_deuda;
        public $tipo_mov;
        public $descripcion;
        public $cant;
        public $valor_unit;
        public $valor_total;
        public $saldo;   
        public $fecha;
        public $hora;
        
        public function __construct($args=[])
        {
            $this->id_mov = $args['id_mov'] ?? null;
            $this->fk_deuda = $args['fk_deuda'] ?? '';
            $this->tipo_mov = $args['tipo_mov'] ?? '';
            $this->descripcion = $args['descripcion'] ?? '';
            $this->cant = $args['cant'] ?? 1;
            $this->valor_unit = $args['valor_unit'] ?? 0;
            $this->valor_total = $args['valor_total'] ?? 0;
            $this->saldo = $args['saldo'] ?? 0;
            $this->fecha = $args['fecha'] ?? '';
            $this->hora = $args['hora'] ?? '';


        }
        
        public function validar(){

            if(!$this->fk_deuda){
                self::$alertas['error']['fk_deuda'][] = "Falta relacionar la deuda master";
            }else if(!preg_match('/^[0-9]{1,20}$/', $this->fk_deuda)){
                self::$alertas['error']['fk_deuda'][] = "El valor de fk_deuda no es válido";
            }else{
                $this->fk_deuda = (int) trim($this->fk_deuda);
            }

            if(!$this->tipo_mov){
                self::$alertas['error']['tipo_mov'][] = "El campo Tipo de Movimiento es obligatorio";
            }else if(!preg_match('/^[ADR]{1}$/', $this->tipo_mov)){
                self::$alertas['error']['tipo_mov'][] = "Debe seleccionar un tipo de movimiento válido";
            }else{
                $this->tipo_mov = trim($this->tipo_mov);
            }

            if(!$this->descripcion){
                self::$alertas['error']['descripcion'][] = "El campo Descripción es obligatorio";
            }else if(!preg_match('/^[a-zA-Z0-9#.\-áéíóúüÁÉÍÓÚñÑ -]{2,500}$/', $this->descripcion)){
                self::$alertas['error']['descripcion'][] = "No debe digitar caracteres No válidos";
            }else{
                $this->descripcion = trim($this->descripcion);
            }

            if(!$this->cant){
                self::$alertas['error']['cant'][] = "El campo cantidad es obligatorio";
            }else if(!preg_match('/^[0-9]{1,3}$/', $this->cant)){
                self::$alertas['error']['cant'][] = "Solo se permiten números";
            }else{
                $this->cant = (int) trim($this->cant);
            }

            if(!$this->valor_unit){
                self::$alertas['error']['valor_unit'][] = "El campo valor unitario es obligatorio";
            }else if(!preg_match('/^[0-9]{2,10}$/', $this->valor_unit)){
                self::$alertas['error']['valor_unit'][] = "Solo se permiten números";
            }else{
                $this->valor_unit = (int) trim($this->valor_unit);
            }

            if(!$this->valor_total){
                self::$alertas['error']['valor_total'][] = "El campo valor total es obligatorio";
            }else if(!preg_match('/^[0-9]{2,10}$/', $this->valor_total)){
                self::$alertas['error']['valor_total'][] = "Solo se permiten números";
            }else{
                $this->valor_total = (int) trim($this->valor_total);
            }
            
            // if(!$this->fecha){
            //     self::$alertas['error']['fecha'][] = "El campo fecha es obligatorio";
            // }else if(!preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $this->fecha)){
            //     self::$alertas['error']['fecha'][] = "Debe seleccionar una fecha válida";
            // }else{
            //     $this->fecha = trim($this->fecha);
            // }

            // if(!$this->hora){
            //     self::$alertas['error']['hora'][] = "El campo hora es obligatorio";
            // }else if(!preg_match('/^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/', $this->hora)){
            //     self::$alertas['error']['hora'][] = "Debe seleccionar una hora válida";
            // }else{
            //     $this->hora = trim($this->hora);
            // }

            return self::$alertas;

        }

        public function getIdMov(){
            return $this->id_mov;
        }

        public function setFkDeuda($id){
            $this->fk_deuda = $id;
        }

        public function getFkDeuda(){
            return $this->fk_deuda;
        }

        public function getValorTotal(){
            return $this->valor_total;
        }

        public function setSaldo($saldo){
            $this->saldo = $saldo;
        }

        public function getSaldo(){
            return $this->saldo;
        }

        public function getTipoMov(){
            return $this->tipo_mov;
        }

        
    }

?>