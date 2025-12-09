<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class Empleado {
    private $pdo;

    public function __construct() {
        $this->pdo = (new BD())->conexion();
    }

    // LISTAR (Unimos Empleado con Persona para ver los nombres reales)
    public function listar() {
        $sql = $this->pdo->prepare("
            SELECT e.idEmpleado, e.tipo, e.idEstado,
                p.nombres, p.apellidos, p.dni, p.telefono, p.email
            FROM empleado e
            INNER JOIN persona p ON e.idPersona = p.idPersona
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // AGREGAR (TRANSACCIÓN: Persona -> Empleado)
    public function agregar($nombres, $apellidos, $dni, $telefono, $email, $tipo) {
        try {
            $this->pdo->beginTransaction(); // Iniciamos transacción

            // 1. Insertamos en PERSONA
            $sqlP = $this->pdo->prepare("INSERT INTO persona (nombres, apellidos, dni, telefono, email) VALUES (?,?,?,?,?)");
            $sqlP->execute([$nombres, $apellidos, $dni, $telefono, $email]);
            $idPersona = $this->pdo->lastInsertId(); // Obtenemos el ID generado

            // 2. Insertamos en EMPLEADO
            $sqlE = $this->pdo->prepare("INSERT INTO empleado (idPersona, tipo, idEstado) VALUES (?, ?, 1)");
            $sqlE->execute([$idPersona, $tipo]);

            $this->pdo->commit(); // Guardamos todo
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack(); // Si falla, deshacemos todo
            return false;
        }
    }

    // OBTENER UNO (Para editar)
    public function obtenerUno($id) {
        $sql = $this->pdo->prepare("
            SELECT e.idEmpleado, e.idPersona, e.tipo, e.idEstado,
                p.nombres, p.apellidos, p.dni, p.telefono, p.email
            FROM empleado e
            INNER JOIN persona p ON e.idPersona = p.idPersona
            WHERE e.idEmpleado = ?
        ");
        $sql->execute([$id]);
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    // ACTUALIZAR (Modifica datos en ambas tablas)
    public function actualizar($idEmpleado, $idPersona, $nombres, $apellidos, $dni, $telefono, $email, $tipo, $idEstado) {
        try {
            $this->pdo->beginTransaction();

            // 1. Actualizar Persona
            $sqlP = $this->pdo->prepare("UPDATE persona SET nombres=?, apellidos=?, dni=?, telefono=?, email=? WHERE idPersona=?");
            $sqlP->execute([$nombres, $apellidos, $dni, $telefono, $email, $idPersona]);

            // 2. Actualizar Empleado
            $sqlE = $this->pdo->prepare("UPDATE empleado SET tipo=?, idEstado=? WHERE idEmpleado=?");
            $sqlE->execute([$tipo, $idEstado, $idEmpleado]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // CAMBIAR ESTADO (Eliminación lógica)
    public function cambiarEstado($id, $estado) {
        $sql = $this->pdo->prepare("UPDATE empleado SET idEstado = ? WHERE idEmpleado = ?");
        return $sql->execute([$estado, $id]);
    }

    // BUSCAR
    public function buscar($texto) {
        $texto = "%$texto%";
        $sql = $this->pdo->prepare("
            SELECT e.idEmpleado, e.tipo, e.idEstado, p.nombres, p.apellidos, p.dni 
            FROM empleado e
            INNER JOIN persona p ON e.idPersona = p.idPersona
            WHERE p.nombres LIKE ? OR p.apellidos LIKE ? OR p.dni LIKE ?
        ");
        $sql->execute([$texto, $texto, $texto]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>