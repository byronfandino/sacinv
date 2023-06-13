<?php
namespace Model;
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $alertas = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlerta($tipo, $propiedad, $mensaje) {
        static::$alertas[$tipo][$propiedad] = $mensaje;
    }

    // Validación
    public static function getAlertas() {
        return static::$alertas;
    }

    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    // Consulta SQL para crear un objeto en Memoria
    public static function consultarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);
        // Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }
        
        // liberar la memoria
        $resultado->free();
        // debuguear($array);

        // retornar los resultados
        return $array;
    }

    public static function existeLlaveForanea($campo, $foreignKey){
        $sql = "SELECT * FROM " . static::$tabla . " WHERE " . $campo . " = " . $foreignKey;
        $resultado = self::$db->query($sql);

        // Almacenamos únicamente los id del registro en el arreglo
        $arrayId = [];
        $arrayDescripcion = [];
        while($registro = $resultado->fetch_assoc()){
            array_push($arrayId, (int)$registro[static::$columnasDB[0]]);
            array_push($arrayDescripcion, $registro[static::$columnasDB[1]]);
        }
        // Se retornan dos arreglos para acceder al id y al nombre en el caso específico de los nombres de los archivos que se deben eliminar del servidor
        return [$arrayId , $arrayDescripcion];
    }

    // Crea el objeto en memoria que es igual al de la BD
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value ) {
            if(property_exists( $objeto, $key  )) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        $i=0;
        foreach(static::$columnasDB as $columna) {
            // Colocamos la comparación a 0, para asegurarnos de que sea el primer Id, y no vaya a afectar las llaves foráneas
            if(strpos($columna, 'Id') && $i===0) continue;
            $atributos[$columna] = $this->$columna;
            $i++;
        }
        return $atributos;
    }

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string(trim($value));
        }
        return $sanitizado;
    }

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
          }
        }
    }

    // Registros - CRUD
    public function guardar() {
        $resultado = '';
        $campoId = static::$columnasDB[0];
        // debuguear($this->$id);
        
        if(!is_null($this->$campoId)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

    // Todos los registros
    public static function all($columna) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY " . $columna . " ASC ";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE " . static::$columnasDB[0] . "=" . $id;
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT ${limite}";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    public static function where($columna, $valor){
        $query = " SELECT * FROM " . static::$tabla . " WHERE ${columna} = '${valor}'";
        $resultado = self::consultarSQL($query);
        
        return array_shift($resultado);
    }

    //Consulta plata de SQL (Utilizar cuando los métodos del modelo no son suficientes)
    public static function SQL($query) {
        // echo json_encode($query);
        // exit;
        $resultado = self::consultarSQL($query);
        // debuguear($resultado);
        return $resultado;
    }
    
    // crea un nuevo registro
    public function crear() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        
        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES ('"; 
        $query .= join("', '", array_values($atributos));
        $query .= "') ";

        // if(static::$tabla === 'tblproducto_oferta'){
        //     debuguear($query);
        // }
        // Resultado de la consulta
        $resultado = self::$db->query($query);

        return [
           'resultado' =>  $resultado,
           'id' => self::$db->insert_id
        ];
    }

    // Actualizar el registro
    public function actualizar() {
        //Obtenemos el nombre del primer campo del arreglo de columnas
         $campoId = static::$columnasDB[0];
         
         // Sanitizar los datos
         $atributos = $this->sanitizarAtributos();

         // Iterar para ir agregando cada campo de la BD
         $valores = [];
         foreach($atributos as $key => $value) {
             $valores[] = "{$key}='{$value}'";
        }
            
        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE " . $campoId . " = '" . self::$db->escape_string($this->$campoId) . "' ";
        $query .= " LIMIT 1 "; 
        
        // debuguear($query);
        
        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // Eliminar un Registro por su ID
    public function eliminar($id) {
        $query = "DELETE FROM "  . static::$tabla . " WHERE " . static::$columnasDB[0] . " = " . self::$db->escape_string($id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        return $resultado;
        // return $query;
    }

}