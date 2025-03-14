<?php
namespace Model;

use PDO;
use PDOException;

class ActiveRecord {

    // Base DE DATOS
    protected static $pdo;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $alertas = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setPDO($pdoConexion) {
        self::$pdo = $pdoConexion;
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
    public static function consultarSQL($stmt) {
        // Consultar la base de datos
        $resultado = $stmt->fetchAll();

        // Iterar los resultados
        $array = [];

        foreach($resultado as $fila){
            $array[] = static::crearObjeto($fila);
        }

        // retornar los resultados
        return $array;
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

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
          }
        }
    }

    // Todos los registros
    public static function all($columna) {
        $stmt = self::$pdo->query('SELECT * FROM ' . static::$tabla . ' ORDER BY ' . $columna . ' ASC ');
        $resultado = self::consultarSQL($stmt);
        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE " . static::$columnasDB[0] . "=" . $id;
        $stmt = self::$pdo->query($query);
        $resultado = self::consultarSQL($stmt);
        return array_shift( $resultado ) ;
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " .$limite;
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    public static function where($columna, $valor){
        // $query = " SELECT * FROM " . static::$tabla . " WHERE ${columna} = '${valor}'";
        $stmt = self::$pdo->query('SELECT * FROM ' . static::$tabla . ' WHERE ' . $columna . ' = ' . $valor);
        $resultado = self::consultarSQL($stmt);
        
        return array_shift($resultado);
    }

    //Consulta plata de SQL (Utilizar cuando los métodos del modelo no son suficientes)
    public static function SQL($query) {
        $stmt = self::$pdo->query($query);
        $resultado = self::consultarSQL($stmt);
        return $resultado;
    }
    
    // crea un nuevo registro
    public function crear() {

        $array_datos = $this->atributos();
        // Eliminamos el primer elemento del arreglo asociativo que es el ID
        array_shift($array_datos);
        
        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($array_datos));
        $query .= " ) VALUES (:"; 
        $query .= join(", :", array_keys($array_datos));
        $query .= ") ";

        // Nueva linea
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Preparar declaración sql
        $resultado = self::$pdo->prepare($query);

        $arrayInsert = [];

        // Recorrer los datos y vincularlos dinámicamente
        foreach ($array_datos as $key => $value) {
            // Detectar el tipo de dato y asignar el tipo correcto
            $tipo = is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            
            // Forzar conversión a int si es un número
            $valor_convertido = ($tipo === PDO::PARAM_INT) ? (int) $value : $value;

            // Vincular el valor
            $resultado->bindValue(":$key", $valor_convertido, $tipo);
        }

        try{
            if($resultado->execute()){
                return true;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Actualizar el registro
    public function actualizar() {

        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //Obtenemos el nombre del primer campo del arreglo de columnas
        $campoId = static::$columnasDB[0];
        
        // Sanitizar los datos
        //Aquí se usaba la función sanitizar atributos
        $array_datos = $this->atributos();

        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        foreach($array_datos as $key => $value){
            // si el key corresponde al campo Id, omitirlo para colocarlo al final de forma manual
            if($key !== $campoId){
                $query .= $key . " = :" . $key. ",";
            }
        }

        // Se elimina la coma (,) que quedó al final de la cadena
        $query = rtrim($query, ', ');

        // agregamos el id al final del query
        $query .= " WHERE " . $campoId . "=:" . $campoId;

        // Preparar la sentencia
        $stmt = self::$pdo->prepare($query);

        //Vinculación de parámetros
        foreach($array_datos as $key => $value){
            // si el key corresponde al campo Id
            $stmt->bindParam(':' . $key, $array_datos[$key]); 
        }
        try{
            if($stmt->execute()){
                return true;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Eliminar un Registro por su ID
    public function eliminar($id) {

        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "DELETE FROM "  . static::$tabla . " WHERE " . static::$columnasDB[0] . " = :" . static::$columnasDB[0];
        $stmt = static::$pdo->prepare($query);
        
        $stmt->bindParam(':' . static::$columnasDB[0], $id);
        
        try{
            if($stmt->execute()){
                return true;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

}