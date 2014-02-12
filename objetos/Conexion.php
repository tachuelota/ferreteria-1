<?php
/* Clase conexion creada para conectar a la base de datos sera llamada cuando se ocupe hacer
alguna peticion a la BD de datos */

class Conexion {

    private static $dsn = 'mysql:dbname=ferreteria;host=localhost';
    private static $usuario = 'root';
    private static $password = 'nEAnn@m';
    
    //Metodo static para conectar a la base de datos
    static function conectar(){
        //Creacion de un objeto conexion
        try {
            $conexion = new PDO(self::$dsn, self::$usuario, self::$password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        }
        catch (PDOException $e) {
            die('Error en la conexión: ' . $e->getMessage());
        }
        //Retornamos la conexion que hicimos a la BD
        return $conexion;
    }
    
    //Motodo static para desconectar de la base de datos
    static function desconectar($conexion){
        //la conexion que recibimos la cerramos
        $conexion = null;
    }
}

?>
