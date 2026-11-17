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
            $stmtUpdate  = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal + ? WHERE idProducto = ?");

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

    // OBTENER DETALLES DE UN INGRESO ESPECÍFICO
    public function obtenerDetallesPorIngreso($idIngreso) {
        $sql = "SELECT di.idDetalleIngreso, di.idProducto, di.cantidad, di.precioCompra, di.MontoTotal, p.nombre
                FROM detalleingreso di
                INNER JOIN producto p ON di.idProducto = p.idProducto
                WHERE di.idIngreso = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idIngreso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ACTUALIZAR INGRESO Y REAJUSTAR STOCK
    public function actualizarIngreso($idIngreso, $idProveedor, $idTrabajador, $detalles) {
        try {
            $this->pdo->beginTransaction();

            // 1. Revertir el stock de los productos del detalle anterior
            $sqlOld = $this->pdo->prepare("SELECT idProducto, cantidad FROM detalleingreso WHERE idIngreso = ?");
            $sqlOld->execute([$idIngreso]);
            $oldDetalles = $sqlOld->fetchAll(PDO::FETCH_ASSOC);

            $stmtRevert = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal - ? WHERE idProducto = ?");
            foreach ($oldDetalles as $old) {
                $stmtRevert->execute([$old['cantidad'], $old['idProducto']]);
            }

            // 2. Eliminar detalles antiguos
            $sqlDel = $this->pdo->prepare("DELETE FROM detalleingreso WHERE idIngreso = ?");
            $sqlDel->execute([$idIngreso]);

            // 3. Actualizar cabecera del ingreso
            $sqlUpdate = $this->pdo->prepare("UPDATE ingreso SET idProveedor = ?, idTrabajador = ? WHERE idIngreso = ?");
            $sqlUpdate->execute([$idProveedor, $idTrabajador, $idIngreso]);

            // 4. Insertar nuevos detalles y sumar el nuevo stock
            $stmtDetalle = $this->pdo->prepare("INSERT INTO detalleingreso (idIngreso, idProducto, cantidad, precioCompra, MontoTotal) VALUES (?, ?, ?, ?, ?)");
            $stmtAddStock = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal + ? WHERE idProducto = ?");

            foreach ($detalles as $d) {
                $total = $d['cantidad'] * $d['precio'];
                $stmtDetalle->execute([$idIngreso, $d['idProducto'], $d['cantidad'], $d['precio'], $total]);
                $stmtAddStock->execute([$d['cantidad'], $d['idProducto']]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function listarIngresos() {
        $sql = "SELECT 
                    i.idIngreso AS id, 
                    i.fecha AS fecha, 
                    i.idProveedor,
                    i.idTrabajador,
                    pr.razonSocial AS proveedor, 
                    CONCAT(p.nombres, ' ', p.apellidos) AS trabajador,
                    IFNULL(SUM(di.MontoTotal), 0) AS total
                FROM ingreso i
                INNER JOIN proveedor pr ON i.idProveedor = pr.idProveedor
                INNER JOIN empleado e ON i.idTrabajador = e.idEmpleado
                INNER JOIN persona p ON e.idPersona = p.idPersona
                LEFT JOIN detalleingreso di ON i.idIngreso = di.idIngreso
                GROUP BY i.idIngreso, i.fecha, i.idProveedor, i.idTrabajador, pr.razonSocial, p.nombres, p.apellidos
                ORDER BY i.fecha DESC";
        
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>