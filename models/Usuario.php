<?php 

namespace Model;

class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'usuario_sistema';
    protected static $columnasDB = ['id_us','cedula_us','nombre_us','nickname_us','password_us','tipo_us','celular_us','email_us','direccion_us','token_us', 'confirmado_us', 'fk_ciudad_us','status_us'];
    
    public $id_us;
    public $cedula_us;
    public $nombre_us;
    public $nickname_us;
    public $password_us;
    public $tipo_us;
    public $celular_us;
    public $email_us;
    public $direccion_us;
    public $token_us;
    public $fk_ciudad_us;
    public $confirmado_us;
    public $status_us;

    public function __construct($args = [])
    {
        $this->id_us = $args['id_us'] ?? null;
        $this->cedula_us = $args['cedula_us'] ?? null;
        $this->nombre_us = $args['nombre_us'] ?? null;
        $this->nickname_us = $args['nickname_us'] ?? null;
        $this->password_us = $args['password_us'] ?? null;
        $this->tipo_us = $args['tipo_us'] ?? null;
        $this->celular_us = $args['celular_us'] ?? null;
        $this->email_us = $args['email_us'] ?? null;
        $this->direccion_us = $args['direccion_us'] ?? null;
        $this->token_us = $args['token_us'] ?? null;
        $this->fk_ciudad_us = $args['fk_ciudad_us'] ?? null;
        $this->confirmado_us = $args['confirmado_us'] ?? 'N';
        $this->status_us = 1; $args['status_us'] ?? 'E';
        
    }

    public function validarlogin(){

        if(!$this->nickname_us){
            self::$alertas['error']['nickname_us'][] = "El campo Nickname es obligatorio";            
        }

        if(!$this->password_us){
            self::$alertas['error']['password_us'][] = "El campo Contraseña es obligatorio";
        }

        if(strlen($this->password_us)<8){
            self::$alertas['error']['password_us'][] = "El campo password debe ser mayor a 8 caracteres";
        }

        return self::$alertas;
    }

    public function validarNuevoUsuario(){
        
        if(!$this->cedula_us){
            self::$alertas['error']['cedula_us'][] = "El campo Cédula es obligatorio";
        }else if(!preg_match('/^[0-9]{1,15}$/', $this->cedula_us)){
            self::$alertas['error']['cedula_us'][] = "El campo Cédula no es válido";
        }else{  
            $this->cedula_us = trim($this->cedula_us);
        }

        if(!$this->nombre_us){
            self::$alertas['error']['nombre_us'][] = "El campo Nombre es obligatorio";
        }else if(!preg_match('/^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{2,100}$/', $this->nombre_us)){
            self::$alertas['error']['nombre_us'][] = "Solo acepta números y/o letras. No se permiten caracteres especiales";
        }else{
            $this->nombre_us = trim($this->nombre_us);
        }       

        if(!$this->nickname_us){
            self::$alertas['error']['nickname_us'][] = "El campo NickName es obligatorio";
        }else if(!preg_match('/^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{2,100}$/', $this->nickname_us)){
            self::$alertas['error']['nickname_us'][] = "Solo acepta números y/o letras. No se permiten caracteres especiales";
        }else{  
            $this->nickname_us = trim($this->nickname_us);
        }

        if(!$this->password_us){
            self::$alertas['error']['password_us'][] = "El campo Password es obligatorio";
        }else if(strlen($this->password_us)<8){
            self::$alertas['error']['password_us'][] = "El campo password debe ser mayor a 8 caracteres";
        }else{
            $this->password_us = trim($this->password_us);
        }
        
        if(!$this->tipo_us){
            self::$alertas['error']['tipo_us'][] = "El campo Tipo de Usuario es obligatorio";
        }else if(!preg_match('/^[AE]{1}$/', $this->tipo_us)){
            self::$alertas['error']['tipo_us'][] = "El campo Tipo de Usuario no es válido";
        }else{  
            $this->tipo_us = trim($this->tipo_us);
        }

        if(!$this->celular_us){
            self::$alertas['error']['celular_us'][] = "El campo Celular es obligatorio";
        }else if(!preg_match('/^[0-9]{10}$/', $this->celular_us)){
            self::$alertas['error']['celular_us'][] = "Se permite únicamente 10 números";
        }else{
            $this->celular_us = trim($this->celular_us);
        }
        
        if(!$this->email_us){
            self::$alertas['error']['email_us'][] = "El campo Email es obligatorio";
        }else if(!filter_var($this->email_us, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error']['email_us'][] = "El campo Email no es válido";
        }else{
            $this->email_us = trim($this->email_us);
        }

        if(!$this->direccion_us){
            self::$alertas['error']['direccion_us'][] = "El campo Dirección es obligatorio";
        }else if(!preg_match('/^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{2,100}$/', $this->direccion_us)){
            self::$alertas['error']['direccion_us'][] = "Solo acepta números y/o letras. No se permiten caracteres especiales";
        }else{  
            $this->direccion_us = trim($this->direccion_us);
        }
        
        if(!$this->fk_ciudad_us){
            self::$alertas['error']['fk_ciudad_us'][] = "El campo Ciudad es obligatorio";
        }else if(!preg_match('/^[0-9]{1,11}$/', $this->fk_ciudad_us)){
            self::$alertas['error']['fk_ciudad_us'][] = "El campo Ciudad no es válido";
        }else{
            $this->fk_ciudad_us = trim($this->fk_ciudad_us);
        }

        if(!$this->status_us){
            self::$alertas['error']['status_us'][] = "El campo Status es obligatorio";
        }else if(!preg_match('/^[A_Z]{1}$/', $this->status_us)){
            self::$alertas['error']['status_us'][] = "El campo Status no es válido";
        }else{  
            $this->status_us = trim($this->status_us);
        }

        return self::$alertas;
    }

    public function validarEmail(){

        if(!$this->email_us){
            self::$alertas['error']['email_us'][] = "El campo Email es obligatorio";
        }else if(!filter_var($this->email_us, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error']['email_us'][] = "El campo Email no es válido";
        }else{
            $this->email_us = trim($this->email_us);
        }

        return self::$alertas;
    }

    // public function existeUsuario(){
        
    //     $query1 = "SELECT * FROM " . self::$tabla ." WHERE Us_NickName = '" . $this->Us_NickName . "'  LIMIT 1";
    //     $query2 = "SELECT * FROM " . self::$tabla ." WHERE Us_Email = '" . $this->Us_Email . "' LIMIT 1";
        
    //     $resultado1 = self::$db->query($query1);
    //     $resultado2 = self::$db->query($query2);
        
    //     //Si ya existe el Nickname se agrega el error
    //     if($resultado1->num_rows){
    //         self::$alertas['error-reg']['nickname'][] = "Este NickName ya está registrado";
    //         return $resultado1;
    //     }
        
    //     //Si ya existe el Email se agrega el error
    //     if($resultado2->num_rows){
    //         self::$alertas['error-reg']['email'][] = "Este Email ya está registrado";
    //         return $resultado2;
    //     }

    //     //también podemos retornar $resulado1, de todas formas en caso de llegar hasta esta línea quiere decir que pasó las dos anteriores validaciones
    //     return $resultado2;
        
    // }

    public function hashPassword(){
        $this->password_us = password_hash($this->password_us, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token_us = uniqid();
    }

    public function comprobarPasswordDB($passwordUser){
        $resultado=password_verify($passwordUser, $this->password_us);
        
        if (!$resultado || !$this->confirmado_us){
            self::$alertas['error']['password'][] = "El password es incorrecto o el usuario no está confirmado";
        }else{
            return true;
        }
    }

    public function validarPassword(){
        if(!$this->password_us){
            self::$alertas['error']['password'][] = 'El password es un campo obligatorio';
        }
        
        if(strlen($this->password_us)<8){
            self::$alertas['error']['password'][] = 'El password debe tener al menos 8 caractéres';
        }
        
        return self::$alertas;
    }
}

?>