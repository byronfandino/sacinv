<?php
    namespace Model;

    class DeudaMovimiento extends ActiveRecord{
        protected static $tabla = 'deuda_movimiento';
        protected static $columnasDB = ['id_mov', 'fk_deuda', 'tipo_mov', 'descripcion', 'valor', 'fecha', 'hora', 'saldo'];
        
        public $id_mov;
        public $fk_deuda;
        public $tipo_mov;
        public $descripcion;
        public $valor;
        public $fecha;
        public $hora;
        public $saldo;   
    
        public function __construct($args=[])
        {
            $this->id_mov = $args['id_mov'] ?? null;
            $this->fk_deuda = $args['fk_deuda'] ?? '';
            $this->tipo_mov = $args['tipo_mov'] ?? '';
            $this->descripcion = $args['descripcion'] ?? '';
            $this->valor = $args['valor'] ?? 0;
            $this->fecha = $args['fecha'] ?? '';
            $this->hora = $args['hora'] ?? '';
            $this->saldo = $args['saldo'] ?? 0;
        }
        
        public function validar(){

            // if(!$this->fk_deuda){
            //     self::$alertas['error']['fk_deuda'][] = "Falta relacionar la deuda master";
            // }else if(!preg_match('/^[0-9]{1,20}$/', $this->fk_deuda)){
            //     self::$alertas['error']['fk_deuda'][] = "El valor de fk_deuda no es válido";
            // }else{
            //     $this->fk_deuda = (int) trim($this->fk_deuda);
            // }

            if(!$this->tipo_mov){
                self::$alertas['error']['tipo_mov'][] = "El campo Tipo de Movimiento es obligatorio";
            }else if(!preg_match('/^[AD]{1}$/', $this->tipo_mov)){
                self::$alertas['error']['tipo_mov'][] = "Debe seleccionar un tipo de movimiento válido";
            }else{
                $this->tipo_mov = trim($this->tipo_mov);
            }

            if(!$this->descripcion){
                self::$alertas['error']['descripcion'][] = "El campo Descripción es obligatorio";
            }else if(!preg_match('/^[a-zA-Z0-9#.\-áéíóúÁÉÍÓÚñÑ -]{3,500}$/', $this->descripcion)){
                self::$alertas['error']['descripcion'][] = "No debe digitar caracteres No válidos";
            }else{
                $this->descripcion = trim($this->descripcion);
            }

            if(!$this->valor){
                self::$alertas['error']['valor'][] = "El campo valor es obligatorio";
            }else if(!preg_match('/^[0-9]{2,10}$/', $this->valor)){
                self::$alertas['error']['valor'][] = "Solo se permiten valores numéricos";
            }else{
                $this->valor = (int) trim($this->valor);
            }
            
            if(!$this->fecha){
                self::$alertas['error']['fecha'][] = "El campo fecha es obligatorio";
            }else if(!preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $this->fecha)){
                self::$alertas['error']['fecha'][] = "Debe seleccionar una fecha válida";
            }else{
                $this->fecha = trim($this->fecha);
            }

            if(!$this->hora){
                self::$alertas['error']['hora'][] = "El campo hora es obligatorio";
            }else if(!preg_match('/^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/', $this->hora)){
                self::$alertas['error']['hora'][] = "Debe seleccionar una hora válida";
            }else{
                $this->hora = trim($this->hora);
            }

            return self::$alertas;

        }

        // Actualizar llave foránea
        public function setFkDeuda($id){
            $this->fk_deuda = $id;
        }

        // Actualizar llave saldo
        public function setSaldo($saldo){
            $this->saldo = $saldo;
        }

        public function getSaldo(){
            return $this->saldo;
        }
    }

?>