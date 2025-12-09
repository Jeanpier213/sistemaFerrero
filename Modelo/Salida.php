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
}
