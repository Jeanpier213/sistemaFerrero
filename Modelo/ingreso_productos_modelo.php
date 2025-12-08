<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class IngresoProducto {

    private $pdo;

    public function __construct() {
        $this->pdo = (new BD())->conexion();
    }

    public function obtenerProveedores() {
        $sql = $this->pdo->query("SELECT idProveedor, razonSocial FROM proveedor WHERE idEstado = 1");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTrabajadores() {
        $sql = "SELECT e.idEmpleado, CONCAT(p.nombres, ' ', p.apellidos) as nombre 
                FROM empleado e 
                JOIN persona p ON e.idPersona = p.idPersona 
                WHERE e.idEstado = 1";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductos() {
        $sql = "SELECT idProducto, nombre, unidadMedida, precioReferencial FROM producto WHERE idEstado = 1";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarIngreso($idProveedor, $idTrabajador, $detalles) {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO ingreso (fecha, idProveedor, idTrabajador) VALUES (NOW(), ?, ?)");
            $stmt->execute([$idProveedor, $idTrabajador]);
            $idIngreso = $this->pdo->lastInsertId();

            $stmtDetalle = $this->pdo->prepare("INSERT INTO detalleingreso (idIngreso, idProducto, cantidad, precioCompra, MontoTotal) VALUES (?, ?, ?, ?, ?)");
            $stmtUpdate = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal + ? WHERE idProducto = ?");

            foreach ($detalles as $d) {
                $total = $d['cantidad'] * $d['precio'];
                
                $stmtDetalle->execute([$idIngreso, $d['idProducto'], $d['cantidad'], $d['precio'], $total]);
                
                $stmtUpdate->execute([$d['cantidad'], $d['idProducto']]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
?>
