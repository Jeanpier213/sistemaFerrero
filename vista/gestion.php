<?php
require_once '../Modelo/Gestion.php';
$gestionModelo = new Gestion();
$ventas = $gestionModelo->listarVentas() ?? [];
$proformas = $gestionModelo->listarProformasPendientes() ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión y Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 2px solid transparent;
            padding: 0.75rem 1.25rem;
            transition: all 0.2s ease-in-out;
        }
        .nav-tabs .nav-link:hover {
            color: #0d6efd;
        }
        .nav-tabs .nav-link.active#ventas-tab {
            color: #198754 !important;
            border-bottom: 3px solid #198754 !important;
            background-color: transparent !important;
        }
        .nav-tabs .nav-link.active#proformas-tab {
            color: #0d6efd !important;
            border-bottom: 3px solid #0d6efd !important;
            background-color: transparent !important;
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid pt-4 px-4 pb-5">
    
    <!-- ENCABEZADO MEJORADO -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                <i class="bi bi-clock-history fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Historial y Gestión</h4>
                <span class="text-muted small">Consulta de ventas realizadas y seguimiento de proformas pendientes</span>
            </div>
        </div>
    </div>

    <!-- TARJETA PRINCIPAL -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-3 px-4">
            <ul class="nav nav-tabs border-bottom" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold border-0" id="ventas-tab" data-bs-toggle="tab" data-bs-target="#ventas" type="button" role="tab">
                        <i class="bi bi-receipt me-1"></i> Ventas Realizadas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold border-0" id="proformas-tab" data-bs-toggle="tab" data-bs-target="#proformas" type="button" role="tab">
                        <i class="bi bi-file-earmark-text me-1"></i> Proformas Pendientes
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4 pt-3">
            <div class="tab-content" id="myTabContent">
                
                <!-- Pestaña Ventas -->
                <div class="tab-pane fade show active" id="ventas" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th class="ps-3">Fecha</th>
                                    <th>Comprobante</th>
                                    <th>Cliente</th>
                                    <th>Método Pago</th>
                                    <th>Total</th>
                                    <th class="text-center pe-3">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($ventas) > 0): ?>
                                    <?php foreach($ventas as $v): ?>
                                    <tr>
                                        <td class="ps-3 text-muted small"><i class="bi bi-calendar3 me-1"></i><?= $v['fecha'] ?></td>
                                        <td>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-2 px-2 py-1">
                                                <i class="bi bi-receipt-cutoff me-1"></i><?= $v['nroComprobante'] ?>
                                            </span>
                                        </td>
                                        <td class="fw-medium text-dark"><?= $v['cliente'] ?></td>
                                        <td>
                                            <span class="badge bg-light text-dark border rounded-pill px-3 py-1 fw-normal">
                                                <?= $v['metodoPago'] ?>
                                            </span>
                                        </td>
                                        <td class="fw-bold text-success">S/ <?= number_format($v['total'], 2) ?></td>
                                        <td class="text-center pe-3">
                                            <a href="../Controlador/generar_ticket.php?id=<?= $v['idVenta'] ?>" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                                <i class="bi bi-printer me-1"></i> Ticket
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="bi bi-inbox fs-2 d-block mb-2"></i> No hay ventas registradas.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

<!-- Pestaña Proformas -->
<div class="tab-pane fade" id="proformas" role="tabpanel">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-secondary">
                <tr>
                    <th class="ps-3">Fecha Creación</th>
                    <th>Vence el</th>
                    <th>Cliente</th>
                    <th>Presupuesto</th>
                    <th>Estado</th>
                    <th class="text-center pe-3">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($proformas) > 0): ?>
                    <?php 
                    $fechaActual = date('Y-m-d H:i:s'); // Obtenemos la fecha actual
                    foreach($proformas as $p): 
                        // Verificamos si ya venció comparando fechas
                        $estaVencida = ($fechaActual > $p['fechaVencimiento']);
                    ?>
                    <tr class="<?= $estaVencida ? 'table-secondary opacity-75' : '' ?>">
                        <td class="ps-3 text-muted small"><i class="bi bi-calendar3 me-1"></i><?= $p['fecha'] ?></td>
                        <td class="text-danger fw-semibold small"><i class="bi bi-clock-history me-1"></i> <?= $p['fechaVencimiento'] ?></td>
                        <td class="fw-medium text-dark"><?= $p['cliente'] ?></td>
                        <td class="fw-bold text-primary">S/ <?= number_format($p['total'], 2) ?></td>
                        
                        <!-- Columna de Estado -->
                        <td>
                            <?php if ($estaVencida): ?>
                                <span class="badge bg-danger rounded-pill"><i class="bi bi-x-circle me-1"></i> Vencida</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark rounded-pill"><i class="bi bi-hourglass-split me-1"></i> Pendiente</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center pe-3">
                            <?php if ($estaVencida): ?>
                                <!-- Botón bloqueado si está vencida -->
                                <button class="btn btn-sm btn-secondary rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1" disabled title="La proforma ha expirado">
                                    <i class="bi bi-ban"></i> Expirado
                                </button>
                            <?php else: ?>
                                <!-- Botón normal si está vigente -->
                                <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1" onclick="convertirAVenta(<?= $p['idCotizacion'] ?>)">
                                    <i class="bi bi-check-circle"></i> Convertir a Venta
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i> No hay proformas pendientes.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function convertirAVenta(idCotizacion) {
        if(confirm('¿El cliente confirmó la compra?\n\nAl aceptar:\n1. Se generará una Factura automáticamente.\n2. Se descontará el stock de los productos.\n3. La proforma pasará a estado Aprobada.')) {
            
            fetch('../Controlador/ConvertirProformaControlador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ idCotizacion: idCotizacion })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.mensaje);
                    // Abrir el ticket en nueva pestaña
                    window.open('../Controlador/generar_ticket.php?id=' + data.idVenta, '_blank');
                    // Recargar la página para actualizar las tablas
                    location.reload();
                } else {
                    alert(data.mensaje);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Ocurrió un error en el servidor.");
            });
        }
    }
</script>
</body>
</html>