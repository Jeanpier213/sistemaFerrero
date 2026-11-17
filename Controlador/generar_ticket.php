<?php
ob_start(); 

$fpdf_path = '../libs/fpdf.php';

// CORRECCIÓN: Prevenir el crash si la librería no está instalada
if (!file_exists($fpdf_path)) {
    die("
        <div style='font-family: Arial; padding: 20px; border: 2px solid red; background: #ffe6e6;'>
            <h2>Falta la librería FPDF</h2>
            <p>El sistema intentó generar el ticket, pero no encontró el archivo PDF.</p>
            <p><b>Solución:</b> Crea una carpeta llamada <code>libs</code> al mismo nivel que tus carpetas Modelo/Controlador, y coloca adentro el archivo <code>fpdf.php</code> y su carpeta <code>font</code>.</p>
        </div>
    ");
}

require_once $fpdf_path;
require_once '../Configuraciones/bd.php'; 

if (!isset($_GET['id']) || $_GET['id'] === 'undefined') {
    die("Error: No se proporcionó un ID de venta válido.");
}

$idVenta = $_GET['id'];
$pdo = (new bd())->conexion();

$sqlVenta = $pdo->prepare("SELECT v.*, c.nombres, c.numeroDoc FROM venta v INNER JOIN cliente c ON v.idCliente = c.idCliente WHERE v.idVenta = ?");
$sqlVenta->execute([$idVenta]);
$venta = $sqlVenta->fetch(PDO::FETCH_ASSOC);

if (!$venta) die("Error: Venta no encontrada.");

$sqlDetalle = $pdo->prepare("SELECT dv.*, p.nombre FROM detalleventa dv INNER JOIN producto p ON dv.idProducto = p.idProducto WHERE dv.idVenta = ?");
$sqlDetalle->execute([$idVenta]);
$detalles = $sqlDetalle->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF('P', 'mm', array(80, 200)); 
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 5, utf8_decode('FERRETERÍA SANTOS'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(60, 5, 'RUC: 10709442985', 0, 1, 'C');
$pdf->Cell(60, 5, 'Av. Chulucanas Mz D10 Lt9, Piura', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(60, 5, strtoupper($venta['tipoComprobante']) . ': ' . $venta['nroComprobante'], 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(60, 5, 'Fecha: ' . $venta['fecha'], 0, 1, 'L');
$pdf->Cell(60, 5, 'Cliente: ' . utf8_decode($venta['nombres']), 0, 1, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10, 5, 'Cant', 'B', 0, 'L');
$pdf->Cell(35, 5, 'Producto', 'B', 0, 'L');
$pdf->Cell(15, 5, 'Sub', 'B', 1, 'R');

$pdf->SetFont('Arial', '', 8);
foreach ($detalles as $item) {
    $pdf->Cell(10, 5, $item['cantidad'], 0, 0, 'L');
    $pdf->Cell(35, 5, substr(utf8_decode($item['nombre']), 0, 18), 0, 0, 'L');
    $pdf->Cell(15, 5, 'S/ ' . number_format($item['subtotal'], 2), 0, 1, 'R');
}

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(45, 5, 'TOTAL:', 0, 0, 'R');
$pdf->Cell(15, 5, 'S/ ' . number_format($venta['total'], 2), 0, 1, 'R');

ob_end_clean();
$pdf->Output('I', 'Ticket_' . $venta['nroComprobante'] . '.pdf');
?>