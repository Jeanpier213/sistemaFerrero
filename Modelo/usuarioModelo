<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class Usuario {

    private $pdo;

    public function __construct() {
        $this->pdo = (new BD())->conexion();
    }

    // REGISTRAR

    public function registrar($idTrabajador, $username, $password) {
    $passHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Intenta insertar
    $sql = $this->pdo->prepare("
        INSERT INTO usuario (idTrabajador, username, passwordHash, idEstado)
        VALUES (?, ?, ?, 1)
    ");
    
    try {
        if ($sql->execute([$idTrabajador, $username, $passHash])) {
            return true;
        } else {
            // SI FALLA, ESTO TE MOSTRARÁ EL ERROR EXACTO EN PANTALLA
            echo "<pre>";
            print_r($sql->errorInfo());
            echo "</pre>";
            die("Error SQL al intentar guardar");
        }
    } catch (PDOException $e) {
        // SI ES ERROR DE LLAVE FORÁNEA O CONEXIÓN
        die("ERROR CRÍTICO DE BD: " . $e->getMessage());
    }
    }

    // VALIDAR SI EL USERNAME YA ESTÁ EN USO
    public function existeUsuario($username) {
        $sql = $this->pdo->prepare("SELECT COUNT(*) FROM usuario WHERE username = ?");
        $sql->execute([$username]);
        return $sql->fetchColumn() > 0;
    }

    // VALIDAR SI EL TRABAJADOR EXISTE (Para que no se registre cualquiera)
    public function existeTrabajador($idTrabajador) {
        $sql = $this->pdo->prepare("SELECT COUNT(*) FROM empleado WHERE idEmpleado = ?");
        $sql->execute([$idTrabajador]);
        return $sql->fetchColumn() > 0;
    }
}
?>
