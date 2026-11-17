<?php
require_once __DIR__ . '/../Modelo/Dashboard.php';

$dashboardModelo = new Dashboard();

// Pedimos los datos al modelo
$ventasHoy = $dashboardModelo->obtenerVentasDelDia();
$cantidadVentas = $dashboardModelo->obtenerCantidadVentasHoy();
$alertasStock = $dashboardModelo->obtenerAlertasStock();

// Finalmente, cargamos la vista (pantalla)
require_once __DIR__ . '/../Vista/inicio.php';
?>