<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../Modelo/Cotizacion.php';

$datosJSON = file_get_contents("php://input");
$datos = json_decode($datosJSON, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($datos)) {
    
    $cotizacionModelo = new Cotizacion();
    
    $idCliente = $datos['idCliente'];
    $total = $datos['total'];
    $detalles = $datos['carrito']; 

    // Registramos y obtenemos el número de cotización
    $idCotizacion = $cotizacionModelo->registrarCotizacion($idCliente, $total, $detalles);

    if ($idCotizacion) {
        echo json_encode([
            "status" => "success", 
            "mensaje" => "Proforma generada con éxito (Válida por 7 días).",
            "idCotizacion" => $idCotizacion
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "mensaje" => "Error al generar la cotización."
        ]);
    }
}
?>