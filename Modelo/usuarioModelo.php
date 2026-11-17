<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class Usuario {

    private $pdo;

    public function __construct() {
        $this->pdo = (new BD())->conexion();
    }

    // LISTAR USUARIOS CON DATOS DEL EMPLEADO Y PERSONA
    public function listar() {
        $sql = $this->pdo->prepare("
            SELECT u.idUsuario, u.idTrabajador, u.username, u.idEstado,
                   p.nombres, p.apellidos, p.dni
            FROM usuario u
            LEFT JOIN empleado e ON u.idTrabajador = e.idEmpleado
            LEFT JOIN persona p ON e.idPersona = p.idPersona
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // REGISTRAR NUEVO USUARIO
    public function registrar($idTrabajador, $username, $password) {
        $passHash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = $this->pdo->prepare("
            INSERT INTO usuario (idTrabajador, username, passwordHash, idEstado)
            VALUES (?, ?, ?, 1)
        ");
        
        try {
            return $sql->execute([$idTrabajador, $username, $passHash]);
        } catch (PDOException $e) {
            die("ERROR CRÍTICO DE BD: " . $e->getMessage());
        }
    }

    // ACTUALIZAR USUARIO
    public function actualizar($idUsuario, $username, $password, $idEstado) {
        if (!empty($password)) {
            $passHash = password_hash($password, PASSWORD_DEFAULT);
            $sql = $this->pdo->prepare("UPDATE usuario SET username = ?, passwordHash = ?, idEstado = ? WHERE idUsuario = ?");
            return $sql->execute([$username, $passHash, $idEstado, $idUsuario]);
        } else {
            $sql = $this->pdo->prepare("UPDATE usuario SET username = ?, idEstado = ? WHERE idUsuario = ?");
            return $sql->execute([$username, $idEstado, $idUsuario]);
        }
    }

    // VALIDAR SI EL USERNAME YA ESTÁ EN USO
    public function existeUsuario($username) {
        $sql = $this->pdo->prepare("SELECT COUNT(*) FROM usuario WHERE username = ?");
        $sql->execute([$username]);
        return $sql->fetchColumn() > 0;
    }

    // VALIDAR SI EL TRABAJADOR EXISTE
    public function existeTrabajador($idTrabajador) {
        $sql = $this->pdo->prepare("SELECT COUNT(*) FROM empleado WHERE idEmpleado = ?");
        $sql->execute([$idTrabajador]);
        return $sql->fetchColumn() > 0;
    }
}
?>