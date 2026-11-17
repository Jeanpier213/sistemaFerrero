<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class Salida {
    private $pdo;

    public function __construct() {
        $this->pdo = (new BD())->conexion();
    }

    public function obtenerTrabajadores() {
        $sql = "SELECT e.idEmpleado, CONCAT(p.nombres, ' ', p.apellidos) as nombre 
                FROM empleado e 
                JOIN persona p ON e.idPersona = p.idPersona 
                WHERE e.idEstado = 1";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductos() {
        $sql = "SELECT idProducto, nombre, cantidadTotal FROM producto WHERE idEstado = 1";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // REGISTRAR NUEVA SALIDA
    public function registrarSalidaConDetalle($fecha, $idTrabajador, $motivo, $detallesArray) {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO salida (fecha, idTrabajador, motivo) VALUES (?, ?, ?)");
            $stmt->execute([$fecha, $idTrabajador, $motivo]);
            $idSalida = $this->pdo->lastInsertId();

            $insertDetalle = $this->pdo->prepare("INSERT INTO detallesalida (idSalida, idProducto, cantidad) VALUES (?, ?, ?)");
            $updateProducto = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal - ? WHERE idProducto = ?");

            foreach ($detallesArray as $d) {
                $insertDetalle->execute([$idSalida, $d['idProducto'], $d['cantidad']]);
                $updateProducto->execute([$d['cantidad'], $d['idProducto']]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // ACTUALIZAR SALIDA Y REAJUSTAR STOCK
    public function editarSalidaConDetalle($idSalida, $fecha, $idTrabajador, $motivo, $detallesArray) {
        try {
            $this->pdo->beginTransaction();

            // 1) Actualizar cabecera
            $stmt = $this->pdo->prepare("UPDATE salida SET fecha = ?, idTrabajador = ?, motivo = ? WHERE idSalida = ?");
            $stmt->execute([$fecha, $idTrabajador, $motivo, $idSalida]);

            // 2) Obtener stock anterior del detalle para revertir (sumar)
            $sqlOld = $this->pdo->prepare("SELECT idProducto, cantidad FROM detallesalida WHERE idSalida = ?");
            $sqlOld->execute([$idSalida]);
            $detallesPrevios = $sqlOld->fetchAll(PDO::FETCH_ASSOC);

            $updateStock = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal + ? WHERE idProducto = ?");
            foreach ($detallesPrevios as $dp) {
                $updateStock->execute([$dp['cantidad'], $dp['idProducto']]);
            }

            // 3) Eliminar detalle anterior
            $stmtDel = $this->pdo->prepare("DELETE FROM detallesalida WHERE idSalida = ?");
            $stmtDel->execute([$idSalida]);

            // 4) Insertar nuevo detalle y restar nuevo stock
            $stmtDetalle = $this->pdo->prepare("INSERT INTO detallesalida (idSalida, idProducto, cantidad) VALUES (?, ?, ?)");
            $stmtStock = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal - ? WHERE idProducto = ?");

            foreach ($detallesArray as $d) {
                $stmtDetalle->execute([$idSalida, $d['idProducto'], $d['cantidad']]);
                $stmtStock->execute([$d['cantidad'], $d['idProducto']]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // LISTAR SALIDAS AGRUPADAS (Para el historial)
    public function listarSalidasAgrupadas() {
        $sql = "SELECT 
                    s.idSalida AS id, 
                    s.fecha, 
                    s.motivo, 
                    s.idTrabajador,
                    CONCAT(p.nombres, ' ', p.apellidos) AS trabajador,
                    SUM(ds.cantidad) AS totalProductos
                FROM salida s
                INNER JOIN empleado e ON s.idTrabajador = e.idEmpleado
                INNER JOIN persona p ON e.idPersona = p.idPersona
                LEFT JOIN detallesalida ds ON s.idSalida = ds.idSalida
                GROUP BY s.idSalida, s.fecha, s.motivo, s.idTrabajador, p.nombres, p.apellidos
                ORDER BY s.fecha DESC, s.idSalida DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // OBTENER DETALLES DE UNA SALIDA ESPECÍFICA (Para el modal de edición)
    public function obtenerDetallesPorSalida($idSalida) {
        $sql = $this->pdo->prepare("
            SELECT ds.idDetalleSalida, ds.idProducto, ds.cantidad, p.nombre 
            FROM detallesalida ds
            INNER JOIN producto p ON ds.idProducto = p.idProducto
            WHERE ds.idSalida = ?
        ");
        $sql->execute([$idSalida]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>