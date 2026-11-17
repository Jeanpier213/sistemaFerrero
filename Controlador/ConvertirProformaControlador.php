<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../Modelo/Gestion.php';

// Leer los datos JSON que envía el botón
$datosJSON = file_get_contents("php://input");
$datos = json_decode($datosJSON, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($datos['idCotizacion'])) {
    
    $modelo = new Gestion();
    $idVenta = $modelo->convertirProformaAVenta($datos['idCotizacion']);

    if ($idVenta) {
        echo json_encode([
            "status" => "success", 
            "mensaje" => "¡Proforma convertida a venta exitosamente! El stock ha sido descontado.",
            "idVenta" => $idVenta
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "mensaje" => "Error al convertir la proforma. Verifique que haya stock suficiente."
        ]);
    }
}
?>