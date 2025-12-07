<?php

class ConexionModelo {
    
    public function conectar($servidor, $usuario, $clave, $bd) {
        try {
            $dsn = "mysql:host=$servidor;dbname=$bd;port=3306;charset=utf8";
            
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $conexion = new PDO($dsn, $usuario, $clave, $opciones);
            
            return $conexion;
        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }
}
?>
