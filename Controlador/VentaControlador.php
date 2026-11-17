<?php
// Permitir recibir JSON desde Javascript
header('Content-Type: application/json');

require_once __DIR__ . '/../Modelo/Venta.php';

// Capturar los datos enviados por Fetch API desde la vista
$datosJSON = file_get_contents("php://input");
$datos = json_decode($datosJSON, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($datos)) {
    
    $ventaModelo = new Venta();
    
    // Asignar variables desde el JSON
    $idCliente = $datos['idCliente'];
    $tipoComprobante = $datos['tipoComprobante'];
    $metodoPago = $datos['metodoPago'];
    $total = $datos['total'];
    $detalles = $datos['carrito']; // Array de productos

    // Generar un número de comprobante simulado (Ej: B001-00045)
    // En un sistema real, esto se consulta a la BD para obtener el último correlativo
    $prefijo = ($tipoComprobante === 'Factura') ? 'F001-' : 'B001-';
    $nroComprobante = $prefijo . str_pad(rand(1, 99999), 5, "0", STR_PAD_LEFT);

    // ... (código existente)
    $idVenta = $ventaModelo->registrarVenta($idCliente, $tipoComprobante, $nroComprobante, $total, $metodoPago, $detalles);

    if ($idVenta) {
        echo json_encode([
            "status" => "success", 
            "mensaje" => "Venta registrada con éxito.",
            "comprobante" => $nroComprobante,
            "idVenta" => $idVenta // <-- CORRECCIÓN: Mandar el ID al JavaScript
        ]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "Error crítico."]);
    }
}
?>