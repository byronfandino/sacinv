<?php 

namespace Model;

class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'tblusuario_sistema';
    protected static $columnasDB = ['Us_Id','Us_Nombre','Us_NickName','Us_Telefono','Us_Email','Us_Password','Us_Status','Us_TipoUsuario','Us_Confirmado','Us_Token'];
    
    public $Us_Id;
    public $Us_Nombre;
    public $Us_NickName;
    public $Us_Telefono;
    public $Us_Email;
    public $Us_Password;
    public $Us_Status;
    public $Us_TipoUsuario;
    public $Us_Confirmado;
    public $Us_Token;

    public function __construct($args = [])
    {
        $this->Us_Id = $args['Us_Id'] ?? null;
        $this->Us_Nombre = $args['Us_Nombre'] ?? '';
        $this->Us_NickName = $args['Us_NickName'] ?? '';
        $this->Us_Telefono = $args['Us_Telefono'] ?? '';
        $this->Us_Email = $args['Us_Email'] ?? '';
        $this->Us_Password = $args['Us_Password'] ?? '';
        $this->Us_Status = $args['Us_Status'] ?? 'E';
        $this->Us_TipoUsuario = $args['Us_TipoUsuario'] ?? 'U';
        $this->Us_Confirmado = $args['Us_Confirmado'] ?? 0;
        $this->Us_Token = $args['Us_Token'] ?? '';
    }

    public function validarlogin(){

        if(!$this->Us_NickName){
            self::$alertas['error-log']['nickname'][] = "El campo Nickname es obligatorio";            
        }

        if(!$this->Us_Password){
            self::$alertas['error-log']['password'][] = "El campo Contraseña es obligatorio";
        }

        if(strlen($this->Us_Password)<8){
            self::$alertas['error-log']['password'][] = "El campo password debe ser mayor a 8 caracteres";
        }

        return self::$alertas;
    }

    public function validarNuevoUsuario(){
        
        if(!$this->Us_Nombre){
            self::$alertas['error-reg']['nombre'][] = "El campo Nombre es obligatorio";
        }
        if(!$this->Us_NickName){
            self::$alertas['error-reg']['nickname'][] = "El campo NickName es obligatorio";
        }
        if(!$this->Us_Telefono){
            self::$alertas['error-reg']['telefono'][] = "El campo Teléfono es obligatorio";
        }
        if(!$this->Us_Email){
            self::$alertas['error-reg']['email'][] = "El campo Correo electrónico es obligatorio";
        }
        if(!$this->Us_Password){
            self::$alertas['error-reg']['password'][] = "El campo Contraseña es obligatorio";
        }
        if(strlen($this->Us_Password)<8){
            self::$alertas['error-reg']['password'][] = "El password debe ser mayor a 8 caracteres";
        }

        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->Us_Email){
            self::$alertas['error-olvide']['email'][] = "El campo Email es obligatorio";
        }

        return self::$alertas;
    }

    public function existeUsuario(){
        
        $query1 = "SELECT * FROM " . self::$tabla ." WHERE Us_NickName = '" . $this->Us_NickName . "'  LIMIT 1";
        $query2 = "SELECT * FROM " . self::$tabla ." WHERE Us_Email = '" . $this->Us_Email . "' LIMIT 1";
        
        $resultado1 = self::$db->query($query1);
        $resultado2 = self::$db->query($query2);
        
        //Si ya existe el Nickname se agrega el error
        if($resultado1->num_rows){
            self::$alertas['error-reg']['nickname'][] = "Este NickName ya está registrado";
            return $resultado1;
        }
        
        //Si ya existe el Email se agrega el error
        if($resultado2->num_rows){
            self::$alertas['error-reg']['email'][] = "Este Email ya está registrado";
            return $resultado2;
        }

        //también podemos retornar $resulado1, de todas formas en caso de llegar hasta esta línea quiere decir que pasó las dos anteriores validaciones
        return $resultado2;
        
    }

    public function hashPassword(){
        $this->Us_Password = password_hash($this->Us_Password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->Us_Token = uniqid();
    }

    public function comprobarPasswordDB($passwordUser){
        $resultado=password_verify($passwordUser, $this->Us_Password);
        
        if (!$resultado || !$this->Us_Confirmado){
            self::$alertas['error-log']['password'][] = "El password es incorrecto o el usuario no está confirmado";
        }else{
            return true;
        }
    }

    public function validarPassword(){
        if(!$this->Us_Password){
            self::$alertas['error-recuperar']['password'][] = 'El password es un campo obligatorio';
        }
        
        if(strlen($this->Us_Password)<8){
            self::$alertas['error-recuperar']['password'][] = 'El password debe tener al menos 8 caractéres';
        }
        
        return self::$alertas;
    }
}

?>