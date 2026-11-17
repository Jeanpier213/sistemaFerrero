<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class Gestion {
    private $pdo;

    public function __construct() {
        $this->pdo = (new bd())->conexion();
    }

    // Listar las ventas recientes (Ej: Últimos 50 registros)
    public function listarVentas() {
        $sql = $this->pdo->query("
            SELECT v.idVenta, v.fecha, v.nroComprobante, v.total, v.metodoPago, c.nombres as cliente
            FROM venta v
            INNER JOIN cliente c ON v.idCliente = c.idCliente
            ORDER BY v.fecha DESC LIMIT 50
        ");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // Listar solo las proformas que aún no han sido compradas (Pendientes)
    public function listarProformasPendientes() {
        $sql = $this->pdo->query("
            SELECT p.idCotizacion, p.fecha, p.fechaVencimiento, p.total, c.nombres as cliente
            FROM cotizacion p
            INNER JOIN cliente c ON p.idCliente = c.idCliente
            WHERE p.estado = 'Pendiente'
            ORDER BY p.fecha DESC
        ");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    // Nueva función para convertir proforma a venta
    public function convertirProformaAVenta($idCotizacion) {
        try {
            $this->pdo->beginTransaction();

            // 1. Obtener los datos de la cotización
            $sqlCot = $this->pdo->prepare("SELECT * FROM cotizacion WHERE idCotizacion = ? AND estado = 'Pendiente'");
            $sqlCot->execute([$idCotizacion]);
            $cotizacion = $sqlCot->fetch(PDO::FETCH_ASSOC);

            if (!$cotizacion) {
                throw new Exception("La proforma no existe o ya fue procesada.");
            }

            // 2. Crear la Venta (Generamos un comprobante automático)
            $nroComprobante = "F001-" . str_pad(rand(1, 99999), 5, "0", STR_PAD_LEFT);
            $sqlVenta = $this->pdo->prepare("INSERT INTO venta (idCliente, fecha, tipoComprobante, nroComprobante, total, metodoPago) VALUES (?, NOW(), 'Factura', ?, ?, 'Efectivo (Proforma)')");
            $sqlVenta->execute([$cotizacion['idCliente'], $nroComprobante, $cotizacion['total']]);
            $idVenta = $this->pdo->lastInsertId();

            // 3. Pasar los detalles de la proforma a la venta y descontar el stock
            $sqlDetallesCot = $this->pdo->prepare("SELECT * FROM detallecotizacion WHERE idCotizacion = ?");
            $sqlDetallesCot->execute([$idCotizacion]);
            $detalles = $sqlDetallesCot->fetchAll(PDO::FETCH_ASSOC);

            $sqlInsertDetalle = $this->pdo->prepare("INSERT INTO detalleventa (idVenta, idProducto, cantidad, precioVenta, subtotal) VALUES (?, ?, ?, ?, ?)");
            $sqlUpdateStock = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal - ? WHERE idProducto = ?");

            foreach ($detalles as $det) {
                $sqlInsertDetalle->execute([$idVenta, $det['idProducto'], $det['cantidad'], $det['precioVenta'], $det['subtotal']]);
                $sqlUpdateStock->execute([$det['cantidad'], $det['idProducto']]);
            }

            // 4. Cambiar el estado de la cotización para que desaparezca de "Pendientes"
            $sqlUpdateCot = $this->pdo->prepare("UPDATE cotizacion SET estado = 'Aprobada' WHERE idCotizacion = ?");
            $sqlUpdateCot->execute([$idCotizacion]);

            $this->pdo->commit();
            return $idVenta;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
?>