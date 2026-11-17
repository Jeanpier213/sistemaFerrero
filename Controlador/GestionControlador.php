<?php
require_once __DIR__ . '/../Modelo/Gestion.php';

$gestionModelo = new Gestion();

$ventas = $gestionModelo->listarVentas();
$proformas = $gestionModelo->listarProformasPendientes();

// Cargar la vista
require_once __DIR__ . '/../Vista/gestion.php';
?>