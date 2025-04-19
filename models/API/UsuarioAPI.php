<?php 

namespace Model\API;
use Model\ActiveRecord;

class UsuarioAPI extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'usuario_sistema';
    protected static $columnasDB = ['id_us', 'cedula_us', 'nombre_us', 'nickname_us', 'password_us', 'tipo_us', 'celular_us', 'email_us', 'direccion_us', 'token_us', 'confirmado_us', 'status_us', 'fk_ciudad_us', 'nombre_ciudad', 'codigo_depart', 'nombre_depart'];
    

    //Atributos de la clase
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
    public $confirmado_us;
    public $status_us;
    public $fk_ciudad_us;
    public $nombre_ciudad;
    public $codigo_depart;
    public $nombre_depart;


}

?>