<?php
    namespace Model;

    class DeudoresReporte extends ActiveRecord{
        protected static $tabla = 'deuda_movimiento';
        protected static $columnasDB = ['nombre_cliente', 'saldo', 'fecha'];
        
        public $nombre_cliente;
        public $saldo;   
        public $fecha;
       
    }

?>