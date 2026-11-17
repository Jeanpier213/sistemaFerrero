<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class Venta {
    private $pdo;

    public function __construct() {
        $this->pdo = (new bd())->conexion();
    }

    public function registrarVenta($idCliente, $tipoComprobante, $nroComprobante, $total, $metodoPago, $detalles) {
        try {
            // Iniciamos la transacción (bloqueo de seguridad)
            $this->pdo->beginTransaction();

            // 1. Guardar la cabecera de la venta
            $sqlVenta = $this->pdo->prepare("INSERT INTO venta (idCliente, tipoComprobante, nroComprobante, total, metodoPago) VALUES (?, ?, ?, ?, ?)");
            $sqlVenta->execute([$idCliente, $tipoComprobante, $nroComprobante, $total, $metodoPago]);
            
            // Obtener el ID de la venta recién creada
            $idVenta = $this->pdo->lastInsertId();

            // 2. Preparar las consultas para los detalles y el stock
            $sqlDetalle = $this->pdo->prepare("INSERT INTO detalleventa (idVenta, idProducto, cantidad, precioVenta, subtotal) VALUES (?, ?, ?, ?, ?)");
            $sqlStock = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal - ? WHERE idProducto = ?");

            // 3. Recorrer el carrito de compras
            foreach ($detalles as $item) {
                // Guardar el ítem en el detalle de la venta
                $sqlDetalle->execute([$idVenta, $item['idProducto'], $item['cantidad'], $item['precio'], $item['subtotal']]);
                
                // Descontar la cantidad exacta del stock del producto
                $sqlStock->execute([$item['cantidad'], $item['idProducto']]);
            }

// ... (código existente del foreach)
            $this->pdo->commit();
            return $idVenta; // <-- CORRECCIÓN: Devolver el ID en lugar de 'true'

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
?>