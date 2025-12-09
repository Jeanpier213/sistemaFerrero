<?php
require_once("../Configuraciones/bd.php");

class Salida {
    private $pdo;

    public function __construct() {
        $this->pdo = (new BD())->conexion();
    }

    // columnas de la tabla 'salida'
    public function obtenerColumnasSalida() {
        $sql = $this->pdo->query("SHOW COLUMNS FROM salida");
        return $sql->fetchAll(PDO::FETCH_COLUMN);
    }

    // columnas de la tabla 'detalleSalida'
    public function obtenerColumnasDetalle() {
        $sql = $this->pdo->query("SHOW COLUMNS FROM detalleSalida");
        return $sql->fetchAll(PDO::FETCH_COLUMN);
    }

    // listar detalle completo con joins: salida + detalle
   public function listarSalidasConDetalle() {
    $sql = $this->pdo->query("
        SELECT 
            s.idSalida,
            s.fecha,
            s.motivo,
            e.idEmpleado,
            ds.idProducto,
            ds.cantidad AS cantidadSalida
        FROM salida s
        INNER JOIN detalleSalida ds ON ds.idSalida = s.idSalida
        INNER JOIN empleado e ON e.idEmpleado = s.idTrabajador
        ORDER BY s.idSalida DESC, ds.idDetalleSalida ASC
    ");
    return $sql->fetchAll(PDO::FETCH_ASSOC);
}


    // método seguro para registrar salida + detalle en transacción y actualizar stock
    public function registrarSalidaConDetalle($fecha, $idTrabajador, $motivo, $detallesArray) {
        try {
            $this->pdo->beginTransaction();

            // insertar salida
            $stmt = $this->pdo->prepare("INSERT INTO salida (fecha, idTrabajador, motivo) VALUES (?, ?, ?)");
            $stmt->execute([$fecha, $idTrabajador, $motivo]);
            $idSalida = $this->pdo->lastInsertId();

            // preparar statements
            $insertDetalle = $this->pdo->prepare("INSERT INTO detalleSalida (idSalida, idProducto, cantidad) VALUES (?, ?, ?)");
            $updateProducto = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal - ? WHERE idProducto = ?");

            foreach ($detallesArray as $d) {
                $idProducto = $d['idProducto'];
                $cantidad = $d['cantidad'];

                // insertar detalle
                $insertDetalle->execute([$idSalida, $idProducto, $cantidad]);

                // actualizar stock (restar)
                $updateProducto->execute([$cantidad, $idProducto]);
            }

            $this->pdo->commit();
            return $idSalida;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e; // quien llame lo manejará
        }
    }

    public function editarSalidaConDetalle($idSalida, $motivo, $detallesArray) {
    try {
        $this->pdo->beginTransaction();

        // 1) actualizar motivo
        $stmt = $this->pdo->prepare("UPDATE salida SET motivo = ? WHERE idSalida = ?");
        $stmt->execute([$motivo, $idSalida]);

        // 2) obtener stock anterior del detalle para revertir
        $sqlOld = $this->pdo->prepare("SELECT idProducto, cantidad FROM detalleSalida WHERE idSalida = ?");
        $sqlOld->execute([$idSalida]);
        $detallesPrevios = $sqlOld->fetchAll(PDO::FETCH_ASSOC);

        // 3) devolver stock anterior (sumar)
        $updateStock = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal + ? WHERE idProducto = ?");
        foreach ($detallesPrevios as $dp) {
            $updateStock->execute([$dp['cantidad'], $dp['idProducto']]);
        }

        // 4) eliminar detalle anterior
        $stmtDel = $this->pdo->prepare("DELETE FROM detalleSalida WHERE idSalida = ?");
        $stmtDel->execute([$idSalida]);

        // 5) insertar nuevo detalle
        $stmtDetalle = $this->pdo->prepare("INSERT INTO detalleSalida (idSalida, idProducto, cantidad) VALUES (?, ?, ?)");
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
public function obtenerSalidaConDetallePorId($idSalida) {
    $sql = $this->pdo->prepare("
        SELECT 
            s.idSalida,
            s.fecha,
            s.motivo,
            s.idTrabajador,
            ds.idDetalleSalida,
            ds.idProducto,
            ds.cantidad
        FROM salida s
        INNER JOIN detalleSalida ds ON ds.idSalida = s.idSalida
        WHERE s.idSalida = ?
        ORDER BY ds.idDetalleSalida ASC
    ");
    $sql->execute([$idSalida]);
    return $sql->fetchAll(PDO::FETCH_ASSOC);
}

}
