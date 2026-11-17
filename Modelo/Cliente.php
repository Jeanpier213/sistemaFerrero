<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class Cliente {
    private $conexion;

    public function __construct() {
        $this->conexion = (new bd())->conexion();
    }

    // Listar clientes para la vista principal
    public function listarTodos() {
        $sql = $this->conexion->prepare("SELECT * FROM cliente WHERE idEstado = 1 ORDER BY idCliente DESC");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registro normal desde el módulo de clientes
    public function registrar($tipoDoc, $numeroDoc, $nombres, $direccion, $telefono) {
        try {
            $sql = $this->conexion->prepare("INSERT INTO cliente (tipoDoc, numeroDoc, nombres, direccion, telefono, idEstado) VALUES (?, ?, ?, ?, ?, 1)");
            return $sql->execute([$tipoDoc, $numeroDoc, $nombres, $direccion, $telefono]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Registro rápido desde el Punto de Venta (Devuelve el ID para seleccionarlo automático)
    public function registrarRapido($tipoDoc, $numeroDoc, $nombres, $direccion, $telefono) {
        try {
            $sql = $this->conexion->prepare("INSERT INTO cliente (tipoDoc, numeroDoc, nombres, direccion, telefono, idEstado) VALUES (?, ?, ?, ?, ?, 1)");
            if ($sql->execute([$tipoDoc, $numeroDoc, $nombres, $direccion, $telefono])) {
                return $this->conexion->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function actualizar($idCliente, $tipoDoc, $numeroDoc, $nombres, $direccion, $telefono) {
    $sql = "UPDATE cliente SET tipoDoc = ?, numeroDoc = ?, nombres = ?, direccion = ?, telefono = ? WHERE idCliente = ?";
    $stmt = $this->conexion->prepare($sql);
    return $stmt->execute([$tipoDoc, $numeroDoc, $nombres, $direccion, $telefono, $idCliente]);
}
}
?>