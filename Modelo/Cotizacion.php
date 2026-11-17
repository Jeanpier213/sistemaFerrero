<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class Cotizacion {
    private $pdo;

    public function __construct() {
        $this->pdo = (new bd())->conexion();
    }

    public function registrarCotizacion($idCliente, $total, $detalles) {
        try {
            $this->pdo->beginTransaction();

            // 1. Guardar la cabecera (La fecha de vencimiento es 7 días después de hoy)
            $sqlCotizacion = $this->pdo->prepare("
                INSERT INTO cotizacion (idCliente, fecha, fechaVencimiento, total, estado) 
                VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), ?, 'Pendiente')
            ");
            $sqlCotizacion->execute([$idCliente, $total]);
            
            $idCotizacion = $this->pdo->lastInsertId();

            // 2. Guardar los detalles (Los productos cotizados)
            $sqlDetalle = $this->pdo->prepare("
                INSERT INTO detallecotizacion (idCotizacion, idProducto, cantidad, precioVenta, subtotal) 
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($detalles as $item) {
                $sqlDetalle->execute([
                    $idCotizacion, 
                    $item['idProducto'], 
                    $item['cantidad'], 
                    $item['precio'], 
                    $item['subtotal']
                ]);
            }

            $this->pdo->commit();
            return $idCotizacion; // Devolvemos el ID para poder generar su PDF luego

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
?>