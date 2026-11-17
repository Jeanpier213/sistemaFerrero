<?php
require_once __DIR__ . '/../Modelo/Dashboard.php';

$dashboardModelo = new Dashboard();

// Pedimos los datos al modelo
$ventasHoy = $dashboardModelo->obtenerVentasDelDia() ?? 0;
$cantidadVentas = $dashboardModelo->obtenerCantidadVentasHoy() ?? 0;

// Obtenemos los productos reales de la Base de Datos
$listaProductos = $dashboardModelo->obtenerTodosLosProductos();

// Verificamos si hay al menos un producto bajo su umbral para cambiar el banner inferior
$alertaCritica = false;
foreach ($listaProductos as $p) {
    if ($p['cantidadTotal'] <= $p['umbralMinimo']) {
        $alertaCritica = true;
        break;
    }
}

date_default_timezone_set('America/Lima');
$dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
$meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
$fechaActual = $dias[date('w')] . ', ' . date('d') . ' de ' . $meses[date('n') - 1];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .card-stat {
            border-radius: 16px;
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            position: relative;
        }
        .bg-gradient-success { background: linear-gradient(135deg, #20c997 0%, #129c74 100%); }
        .bg-gradient-info { background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%); }
        .bg-icon {
            position: absolute;
            right: -10px;
            bottom: -20px;
            font-size: 8rem;
            opacity: 0.1;
            transform: rotate(-15deg);
        }
        .panel-custom {
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(226, 232, 240, 0.8);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .table-custom th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
            padding: 15px;
        }
        .table-custom td {
            padding: 15px;
            vertical-align: middle;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }
        .btn-quick-action {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #475569;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        .btn-quick-action:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }
        .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .stock-banner-ok {
            background-color: rgba(32, 201, 151, 0.05);
            border-top: 1px solid rgba(32, 201, 151, 0.2);
            padding: 15px;
            text-align: center;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
            margin-top: auto;
        }
        .stock-banner-alert {
            background-color: rgba(220, 53, 69, 0.05);
            border-top: 1px solid rgba(220, 53, 69, 0.2);
            padding: 15px;
            text-align: center;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
            margin-top: auto;
        }
    </style>
</head>
<body>

<div class="container-fluid pt-4 px-4 pb-5">
    
    <!-- ENCABEZADO CON FECHA -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                <i class="bi bi-bar-chart-fill fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">Resumen del Negocio</h4>
                <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i> <?= $fechaActual ?></span>
            </div>
        </div>
        <a href="menu.php?page=pos" class="btn btn-primary shadow-sm rounded-pill px-4 py-2 fw-medium d-flex align-items-center gap-2">
            <i class="bi bi-cart-plus-fill fs-5"></i> Ir a la Caja
        </a>
    </div>

    <!-- TARJETAS FINANCIERAS -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card card-stat bg-gradient-success text-white shadow-sm h-100 p-4">
                <i class="bi bi-cash-coin bg-icon"></i>
                <div class="position-relative z-1">
                    <div class="d-flex align-items-center gap-2 mb-3 opacity-75">
                        <i class="bi bi-wallet2 fs-5"></i>
                        <h6 class="text-uppercase fw-bold mb-0" style="letter-spacing: 1px; font-size: 0.85rem;">Ingresos de Hoy</h6>
                    </div>
                    <h2 class="fw-bold mb-0 display-5">S/ <?= number_format($ventasHoy, 2) ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card card-stat bg-gradient-info text-white shadow-sm h-100 p-4">
                <i class="bi bi-receipt bg-icon"></i>
                <div class="position-relative z-1">
                    <div class="d-flex align-items-center gap-2 mb-3 opacity-75">
                        <i class="bi bi-bag-check fs-5"></i>
                        <h6 class="text-uppercase fw-bold mb-0" style="letter-spacing: 1px; font-size: 0.85rem;">Ventas Completadas</h6>
                    </div>
                    <h2 class="fw-bold mb-0 display-5"><?= $cantidadVentas ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN INFERIOR DIVIDIDA -->
    <div class="row g-4">
        
        <!-- PANEL IZQUIERDO: Resumen de Inventario -->
        <div class="col-lg-7 col-xl-8">
            <div class="card bg-white panel-custom">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                            <i class="bi bi-box-seam-fill text-primary"></i> Estado del Inventario
                        </h5>
                        <p class="text-muted small">Vista rápida de los productos en almacén.</p>
                    </div>
                    <a href="menu.php?page=productos" class="btn btn-sm btn-outline-secondary">Ver todo</a>
                </div>
                
                <div class="card-body p-0 mt-2">
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover table-custom mb-0">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th class="ps-4">Producto</th>
                                    <th class="text-center">Stock Actual</th>
                                    <th class="text-center">Mínimo</th>
                                    <th class="text-end pe-4">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($listaProductos)): ?>
                                    <?php foreach ($listaProductos as $producto): ?>
                                        <?php 
                                            $esCritico = $producto['cantidadTotal'] <= $producto['umbralMinimo'];
                                            $colorIcono = $esCritico ? 'danger' : 'primary';
                                            $textoEstado = $esCritico ? 'Agotándose' : 'En Stock';
                                            $badgeClass = $esCritico ? 'bg-danger text-danger border-danger' : 'bg-success text-success border-success';
                                        ?>
                                        <tr>
                                            <td class="ps-4 fw-medium text-dark">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="bg-<?= $colorIcono ?> bg-opacity-10 text-<?= $colorIcono ?> rounded-circle d-flex justify-content-center align-items-center" style="width: 32px; height: 32px;">
                                                        <i class="bi bi-box-seam"></i>
                                                    </div>
                                                    <?= htmlspecialchars($producto['nombre']) ?>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="fs-6 fw-bold <?= $esCritico ? 'text-danger' : 'text-dark' ?>">
                                                    <?= htmlspecialchars($producto['cantidadTotal']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center align-middle text-muted fw-medium">
                                                <?= htmlspecialchars($producto['umbralMinimo']) ?>
                                            </td>
                                            <td class="text-end pe-4 align-middle">
                                                <span class="badge <?= $badgeClass ?> bg-opacity-10 border border-opacity-25 rounded-pill px-3 py-1">
                                                    <?= $textoEstado ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No hay productos registrados en el inventario.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BANNER DINÁMICO (Cambia si hay productos críticos) -->
                <?php if ($alertaCritica): ?>
                    <div class="stock-banner-alert d-flex justify-content-center align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                        <span class="text-danger fw-bold">Atención Requerida</span>
                        <span class="text-muted small ms-1">Existen productos por debajo de su umbral mínimo.</span>
                    </div>
                <?php else: ?>
                    <div class="stock-banner-ok d-flex justify-content-center align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <span class="text-success fw-bold">Inventario en óptimas condiciones</span>
                        <span class="text-muted small ms-1">No hay productos con stock crítico.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- PANEL DERECHO: Accesos Rápidos -->
        <div class="col-lg-5 col-xl-4">
            <div class="card bg-white panel-custom p-4">
                <h6 class="fw-bold text-dark mb-4"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Accesos Rápidos</h6>
                
                <div class="d-flex flex-column gap-3">
                    <a href="menu.php?page=productos" class="btn-quick-action">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <div class="text-start">
                            <div class="fw-bold text-dark lh-sm">Gestionar Productos</div>
                            <small class="text-muted">Agregar o editar catálogo</small>
                        </div>
                        <i class="bi bi-chevron-right ms-auto opacity-50"></i>
                    </a>

                    <a href="menu.php?page=clientes" class="btn-quick-action">
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="text-start">
                            <div class="fw-bold text-dark lh-sm">Directorio de Clientes</div>
                            <small class="text-muted">Ver cartera de compradores</small>
                        </div>
                        <i class="bi bi-chevron-right ms-auto opacity-50"></i>
                    </a>

                    <a href="menu.php?page=entrada" class="btn-quick-action">
                        <div class="icon-box bg-info bg-opacity-10 text-info">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="text-start">
                            <div class="fw-bold text-dark lh-sm">Registrar Entrada</div>
                            <small class="text-muted">Ingresar nuevo stock al almacén</small>
                        </div>
                        <i class="bi bi-chevron-right ms-auto opacity-50"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>