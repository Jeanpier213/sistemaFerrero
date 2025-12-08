<?php
class LoginModelo {
    
    public function intentarConexion($servidor, $usuario, $clave, $bd) {
        error_reporting(0);

        try {
            $dsn = "mysql:host=$servidor;dbname=$bd;port=3306;charset=utf8";
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            $conexion = new PDO($dsn, $usuario, $clave, $opciones);
            
            return true;

        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }
}
?>