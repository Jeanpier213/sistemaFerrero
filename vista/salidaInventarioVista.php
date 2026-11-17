<?php
require_once __DIR__ . '/../Controlador/salidaControlador.php';

$control = new salidaControlador();

$trabajadores   = $control->obtenerTrabajadores();
$listaProductos = $control->obtenerProductos();
$historialBase  = $control->listarSalidas();

// Cargar detalles para el Modal vía JSON
$historialCompleto = [];
foreach ($historialBase as $h) {
    $h['detalles'] = $control->obtenerDetallesPorSalida($h['id']);
    $historialCompleto[] = $h;
}

$mensaje     = "";
$tipoMensaje = "";

// PROCESAR GUARDAR
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guardar"])) {
    try {
        $exito = $control->guardarSalida(
            $_POST['fecha'],
            $_POST['trabajador'], 
            $_POST['motivo'],
            $_POST['prods_id'] ?? [], 
            $_POST['prods_cant'] ?? []
        );

        if ($exito) {
            echo "<script>window.location.href='menu.php?page=salidas&msg=exito';</script>";
            exit;
        } else {
            $mensaje = "Añada al menos un producto válido con cantidad mayor a 0.";
            $tipoMensaje = "danger";
        }
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
        $tipoMensaje = "danger";
    }
}

// PROCESAR EDITAR
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar_salida"])) {
    try {
        $exito = $control->actualizarSalida(
            $_POST['idSalida'],
            $_POST['fecha'],
            $_POST['trabajador'], 
            $_POST['motivo'],
            $_POST['prods_id'] ?? [], 
            $_POST['prods_cant'] ?? []
        );

        if ($exito) {
            echo "<script>window.location.href='menu.php?page=salidas&msg=exito_edit';</script>";
            exit;
        } else {
            $mensaje = "Ocurrió un error al intentar actualizar la salida.";
            $tipoMensaje = "danger";
        }
    } catch (Exception $e) {
        $mensaje = "Error al actualizar: " . $e->getMessage();
        $tipoMensaje = "danger";
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'exito') {
        $mensaje = "Salida de productos registrada exitosamente.";
        $tipoMensaje = "success";
    } elseif ($_GET['msg'] == 'exito_edit') {
        $mensaje = "Salida de productos actualizada exitosamente.";
        $tipoMensaje = "success";
    }
}
?>

<div class="container-fluid pt-4 px-4 pb-5">
    
    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-danger bg-opacity-10 text-danger rounded-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                <i class="bi bi-box-arrow-up fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Salida de Productos</h4>
                <span class="text-muted small">Registro de mermas, robos, pérdidas y salidas de inventario</span>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipoMensaje ?> alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-<?= $tipoMensaje == 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?> me-2"></i>
            <?= htmlspecialchars($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- FORMULARIO DE NUEVA SALIDA -->
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-header bg-white border-bottom pt-4 px-4">
            <h6 class="fw-bold text-danger mb-0"><i class="bi bi-dash-circle me-2"></i>Registrar Nueva Salida</h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Empleado Responsable</label>
                        <select name="trabajador" class="form-select" required>
                            <option value="" selected disabled>-- Seleccione Empleado --</option>
                            <?php foreach($trabajadores as $t): ?>
                                <option value="<?= $t['idEmpleado'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Motivo</label>
                        <select name="motivo" class="form-select" required>
                            <option value="Venta">Venta</option>
                            <option value="Robo">Robo</option>
                            <option value="Pérdida">Pérdida</option>
                            <option value="Merma por daño">Merma por daño</option>
                            <option value="Uso interno">Uso interno</option>
                            <option value="Devolución a proveedor">Devolución a proveedor</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th style="width: 70%;">Producto</th>
                                <th style="width: 20%;">Cantidad a Retirar</th>
                                <th class="text-center" style="width: 10%;">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-body">
                            <tr>
                                <td>
                                    <select name="prods_id[]" class="form-select select-prod" required>
                                        <option value="" selected disabled>-- Seleccione Producto --</option>
                                        <?php foreach($listaProductos as $prod): ?>
                                            <option value="<?= $prod['idProducto'] ?>">
                                                <?= htmlspecialchars($prod['nombre']) ?> (Stock actual: <?= $prod['cantidadTotal'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="prods_cant[]" class="form-control input-cant" value="1" min="1" step="1" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle" onclick="eliminarFila(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2">
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4" onclick="agregarFila()">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Producto
                    </button>
                    <button type="submit" name="guardar" class="btn btn-danger rounded-pill px-4 py-2 shadow-sm fw-medium">
                        <i class="bi bi-check-circle me-1"></i> Registrar Salida
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TARJETA HISTORIAL -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-text me-2"></i>Historial de Salidas</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="inputBuscarHistorial" class="form-control bg-light border-start-0" placeholder="Buscar por empleado o motivo...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaHistorial">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Fecha</th>
                            <th>Empleado</th>
                            <th>Motivo</th>
                            <th>Total Prod.</th>
                            <th class="pe-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($historialCompleto)): ?>
                            <?php foreach ($historialCompleto as $row): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-danger">#<?= htmlspecialchars($row['id']) ?></td>
                                    <td class="text-muted small"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($row['fecha']) ?></td>
                                    <td class="fw-medium text-dark"><?= htmlspecialchars($row['trabajador']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">
                                            <?= htmlspecialchars($row['motivo']) ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold"><?= htmlspecialchars($row['totalProductos']) ?> un.</td>
                                    <td class="pe-3 text-center">
                                        <button class="btn btn-sm btn-outline-primary rounded-circle" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditarSalida"
                                                onclick="cargarEdicion(<?= $row['id'] ?>)">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> No hay salidas registradas.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL: EDITAR SALIDA -->
<!-- ============================================== -->
<div class="modal fade" id="modalEditarSalida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Salida</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body p-4">
                    <input type="hidden" name="idSalida" id="e_idSalida">

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Fecha</label>
                            <input type="date" name="fecha" id="e_fecha" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Empleado</label>
                            <select name="trabajador" id="e_trabajador" class="form-select" required>
                                <?php foreach($trabajadores as $t): ?>
                                    <option value="<?= $t['idEmpleado'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Motivo</label>
                            <select name="motivo" id="e_motivo" class="form-select" required>
                                <option value="Venta">Venta</option>
                                <option value="Robo">Robo</option>
                                <option value="Pérdida">Pérdida</option>
                                <option value="Merma por daño">Merma por daño</option>
                                <option value="Uso interno">Uso interno</option>
                                <option value="Devolución a proveedor">Devolución a proveedor</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th style="width: 70%;">Producto</th>
                                    <th style="width: 20%;">Cantidad</th>
                                    <th class="text-center" style="width: 10%;">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="e_tabla-body">
                                <!-- Se llena dinámicamente con JS -->
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4 btn-sm" onclick="agregarFilaEdit()">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Producto
                    </button>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="actualizar_salida" class="btn btn-danger rounded-pill px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script>
const historialData = <?= json_encode($historialCompleto) ?>;

const opcionesProductos = `
    <option value="" selected disabled>-- Seleccione Producto --</option>
    <?php foreach($listaProductos as $prod): ?>
        <option value="<?= $prod['idProducto'] ?>">
            <?= addslashes(htmlspecialchars($prod['nombre'])) ?> (Stock: <?= $prod['cantidadTotal'] ?>)
        </option>
    <?php endforeach; ?>
`;

function agregarFila() {
    let tbody = document.getElementById("tabla-body");
    let fila = document.createElement("tr");
    
    fila.innerHTML = `
        <td>
            <select name="prods_id[]" class="form-select select-prod" required>
                ${opcionesProductos}
            </select>
        </td>
        <td>
            <input type="number" name="prods_cant[]" class="form-control input-cant" value="1" min="1" step="1" required>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle" onclick="eliminarFila(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(fila);
}

function eliminarFila(btn) {
    let tbody = document.getElementById("tabla-body");
    if (tbody.rows.length > 1) {
        btn.closest('tr').remove();
    } else {
        alert("Debe incluir al menos un producto.");
    }
}

// Cargar datos en Modal de Edición
function cargarEdicion(idSalida) {
    let salida = historialData.find(item => item.id == idSalida);
    if (!salida) return;

    document.getElementById('e_idSalida').value = salida.id;
    document.getElementById('e_fecha').value = salida.fecha;
    document.getElementById('e_trabajador').value = salida.idTrabajador;
    document.getElementById('e_motivo').value = salida.motivo;

    let tbody = document.getElementById('e_tabla-body');
    tbody.innerHTML = '';

    if (salida.detalles && salida.detalles.length > 0) {
        salida.detalles.forEach(d => {
            let fila = document.createElement("tr");
            fila.innerHTML = `
                <td>
                    <select name="prods_id[]" class="form-select select-prod-edit" required>
                        ${opcionesProductos}
                    </select>
                </td>
                <td>
                    <input type="number" name="prods_cant[]" class="form-control input-cant-edit" value="${d.cantidad}" min="1" step="1" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle" onclick="eliminarFilaEdit(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(fila);
            fila.querySelector('.select-prod-edit').value = d.idProducto;
        });
    } else {
        agregarFilaEdit();
    }
}

function agregarFilaEdit() {
    let tbody = document.getElementById("e_tabla-body");
    let fila = document.createElement("tr");
    
    fila.innerHTML = `
        <td>
            <select name="prods_id[]" class="form-select select-prod-edit" required>
                ${opcionesProductos}
            </select>
        </td>
        <td>
            <input type="number" name="prods_cant[]" class="form-control input-cant-edit" value="1" min="1" step="1" required>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle" onclick="eliminarFilaEdit(this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(fila);
}

function eliminarFilaEdit(btn) {
    let tbody = document.getElementById("e_tabla-body");
    if (tbody.rows.length > 1) {
        btn.closest('tr').remove();
    } else {
        alert("Debe incluir al menos un producto.");
    }
}

document.getElementById('inputBuscarHistorial').addEventListener('keyup', function() {
    let valor = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaHistorial tbody tr');
    
    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(valor) ? '' : 'none';
    });
});
</script>