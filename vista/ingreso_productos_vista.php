<?php
require_once __DIR__ . '/../Controlador/ingreso_productos_controlador.php';

$control = new IngresoProductoControlador();

$proveedores    = $control->obtenerProveedores();
$trabajadores   = $control->obtenerTrabajadores();
$listaProductos = $control->obtenerProductos();
$historialBase  = $control->listarIngresos();

// Cargar detalles de cada ingreso para JavaScript
$historialCompleto = [];
foreach ($historialBase as $h) {
    $h['detalles'] = $control->obtenerDetallesPorIngreso($h['id']);
    $historialCompleto[] = $h;
}

$mensaje     = "";
$tipoMensaje = "";

// PROCESAR GUARDAR
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guardar"])) {
    try {
        $exito = $control->guardarIngreso(
            $_POST['proveedor'], 
            $_POST['trabajador'], 
            $_POST['prods_id'] ?? [], 
            $_POST['prods_cant'] ?? [], 
            $_POST['prods_precio'] ?? []
        );

        if ($exito) {
            echo "<script>window.location.href='menu.php?page=entrada&msg=exito';</script>";
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar_ingreso"])) {
    try {
        $exito = $control->actualizarIngreso(
            $_POST['idIngreso'],
            $_POST['proveedor'], 
            $_POST['trabajador'], 
            $_POST['prods_id'] ?? [], 
            $_POST['prods_cant'] ?? [], 
            $_POST['prods_precio'] ?? []
        );

        if ($exito) {
            echo "<script>window.location.href='menu.php?page=entrada&msg=exito_edit';</script>";
            exit;
        } else {
            $mensaje = "Ocurrió un error al intentar actualizar el ingreso.";
            $tipoMensaje = "danger";
        }
    } catch (Exception $e) {
        $mensaje = "Error al actualizar: " . $e->getMessage();
        $tipoMensaje = "danger";
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'exito') {
        $mensaje = "Ingreso de productos registrado exitosamente.";
        $tipoMensaje = "success";
    } elseif ($_GET['msg'] == 'exito_edit') {
        $mensaje = "Ingreso de productos actualizado exitosamente.";
        $tipoMensaje = "success";
    }
}
?>

<div class="container-fluid pt-4 px-4 pb-5">
    
    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                <i class="bi bi-box-arrow-in-down fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Ingreso de Productos</h4>
                <span class="text-muted small">Registro de compras de mercadería y abastecimiento de inventario</span>
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

    <!-- FORMULARIO DE NUEVO INGRESO -->
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-header bg-white border-bottom pt-4 px-4">
            <h6 class="fw-bold text-primary mb-0"><i class="bi bi-cart-plus me-2"></i>Registrar Nueva Entrada</h6>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Proveedor</label>
                        <select name="proveedor" class="form-select" required>
                            <option value="" selected disabled>-- Seleccione Proveedor --</option>
                            <?php foreach($proveedores as $p): ?>
                                <option value="<?= $p['idProveedor'] ?>"><?= htmlspecialchars($p['razonSocial']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Trabajador Responsable</label>
                        <select name="trabajador" class="form-select" required>
                            <option value="" selected disabled>-- Seleccione Trabajador --</option>
                            <?php foreach($trabajadores as $t): ?>
                                <option value="<?= $t['idEmpleado'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th style="width: 45%;">Producto</th>
                                <th style="width: 15%;">Cantidad</th>
                                <th style="width: 20%;">Precio Compra (S/)</th>
                                <th style="width: 15%;">Subtotal (S/)</th>
                                <th class="text-center" style="width: 5%;">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-body">
                            <tr>
                                <td>
                                    <select name="prods_id[]" class="form-select select-prod" onchange="autoCompletarPrecio(this)" required>
                                        <option value="" selected disabled>-- Seleccione Producto --</option>
                                        <?php foreach($listaProductos as $prod): ?>
                                            <option value="<?= $prod['idProducto'] ?>" data-precio="<?= $prod['precioReferencial'] ?>">
                                                <?= htmlspecialchars($prod['nombre']) ?> (Ref: S/ <?= number_format($prod['precioReferencial'], 2) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="prods_cant[]" class="form-control input-cant" value="1" min="1" step="1" oninput="calcularTotales()" required>
                                </td>
                                <td>
                                    <input type="number" name="prods_precio[]" class="form-control input-precio" step="0.01" min="0" placeholder="0.00" oninput="calcularTotales()" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control bg-light input-subtotal fw-bold text-dark" value="0.00" readonly>
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

                    <div class="d-flex align-items-center gap-4">
                        <div class="text-end">
                            <span class="text-muted small d-block">TOTAL ENTRADA</span>
                            <span class="fs-4 fw-bold text-success" id="totalGeneral">S/ 0.00</span>
                        </div>
                        <button type="submit" name="guardar" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-medium">
                            <i class="bi bi-check-circle me-1"></i> Registrar Ingreso
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TARJETA HISTORIAL CON BOTÓN DE EDITAR -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-text me-2"></i>Historial de Ingresos</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="inputBuscarHistorial" class="form-control bg-light border-start-0" placeholder="Buscar por proveedor o trabajador...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaHistorial">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Fecha Registro</th>
                            <th>Proveedor</th>
                            <th>Trabajador</th>
                            <th>Total (S/)</th>
                            <th class="pe-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($historialCompleto)): ?>
                            <?php foreach ($historialCompleto as $row): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-primary">#<?= htmlspecialchars($row['id']) ?></td>
                                    <td class="text-muted small"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($row['fecha']) ?></td>
                                    <td class="fw-medium text-dark"><?= htmlspecialchars($row['proveedor']) ?></td>
                                    <td><?= htmlspecialchars($row['trabajador']) ?></td>
                                    <td class="fw-bold text-success">S/ <?= number_format($row['total'], 2) ?></td>
                                    <td class="pe-3 text-center">
                                        <button class="btn btn-sm btn-outline-primary rounded-circle" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditarIngreso"
                                                onclick="cargarEdicion(<?= $row['id'] ?>)">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> No hay ingresos registrados.
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
<!-- MODAL: EDITAR INGRESO -->
<!-- ============================================== -->
<div class="modal fade" id="modalEditarIngreso" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Ingreso de Productos</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body p-4">
                    <input type="hidden" name="idIngreso" id="e_idIngreso">

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Proveedor</label>
                            <select name="proveedor" id="e_proveedor" class="form-select" required>
                                <?php foreach($proveedores as $p): ?>
                                    <option value="<?= $p['idProveedor'] ?>"><?= htmlspecialchars($p['razonSocial']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Trabajador Responsable</label>
                            <select name="trabajador" id="e_trabajador" class="form-select" required>
                                <?php foreach($trabajadores as $t): ?>
                                    <option value="<?= $t['idEmpleado'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th style="width: 45%;">Producto</th>
                                    <th style="width: 15%;">Cantidad</th>
                                    <th style="width: 20%;">Precio Compra (S/)</th>
                                    <th style="width: 15%;">Subtotal (S/)</th>
                                    <th class="text-center" style="width: 5%;">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="e_tabla-body">
                                <!-- Se llena dinámicamente con JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2">
                        <button type="button" class="btn btn-outline-primary rounded-pill px-4" onclick="agregarFilaEdit()">
                            <i class="bi bi-plus-circle me-1"></i> Agregar Producto
                        </button>

                        <div class="text-end">
                            <span class="text-muted small d-block">NUEVO TOTAL</span>
                            <span class="fs-4 fw-bold text-success" id="e_totalGeneral">S/ 0.00</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="actualizar_ingreso" class="btn btn-primary rounded-pill px-4">Guardar Cambios</button>
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
        <option value="<?= $prod['idProducto'] ?>" data-precio="<?= $prod['precioReferencial'] ?>">
            <?= addslashes(htmlspecialchars($prod['nombre'])) ?> (Ref: S/ <?= number_format($prod['precioReferencial'], 2) ?>)
        </option>
    <?php endforeach; ?>
`;

function agregarFila() {
    let tbody = document.getElementById("tabla-body");
    let fila = document.createElement("tr");
    
    fila.innerHTML = `
        <td>
            <select name="prods_id[]" class="form-select select-prod" onchange="autoCompletarPrecio(this)" required>
                ${opcionesProductos}
            </select>
        </td>
        <td>
            <input type="number" name="prods_cant[]" class="form-control input-cant" value="1" min="1" step="1" oninput="calcularTotales()" required>
        </td>
        <td>
            <input type="number" name="prods_precio[]" class="form-control input-precio" step="0.01" min="0" placeholder="0.00" oninput="calcularTotales()" required>
        </td>
        <td>
            <input type="text" class="form-control bg-light input-subtotal fw-bold text-dark" value="0.00" readonly>
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
        calcularTotales();
    } else {
        alert("Debe incluir al menos un producto.");
    }
}

function autoCompletarPrecio(select) {
    let option = select.options[select.selectedIndex];
    let precio = option.getAttribute('data-precio') || '';
    let fila = select.closest('tr');
    let inputPrecio = fila.querySelector('.input-precio');
    
    if (precio) {
        inputPrecio.value = parseFloat(precio).toFixed(2);
    }
    calcularTotales();
}

function calcularTotales() {
    let filas = document.querySelectorAll('#tabla-body tr');
    let totalGeneral = 0;

    filas.forEach(fila => {
        let cant = parseFloat(fila.querySelector('.input-cant').value) || 0;
        let precio = parseFloat(fila.querySelector('.input-precio').value) || 0;
        let subtotal = cant * precio;

        fila.querySelector('.input-subtotal').value = subtotal.toFixed(2);
        totalGeneral += subtotal;
    });

    document.getElementById('totalGeneral').textContent = 'S/ ' + totalGeneral.toFixed(2);
}

// Cargar datos en Modal de Edición
function cargarEdicion(idIngreso) {
    let ingreso = historialData.find(item => item.id == idIngreso);
    if (!ingreso) return;

    document.getElementById('e_idIngreso').value = ingreso.id;
    document.getElementById('e_proveedor').value = ingreso.idProveedor;
    document.getElementById('e_trabajador').value = ingreso.idTrabajador;

    let tbody = document.getElementById('e_tabla-body');
    tbody.innerHTML = '';

    if (ingreso.detalles && ingreso.detalles.length > 0) {
        ingreso.detalles.forEach(d => {
            let fila = document.createElement("tr");
            fila.innerHTML = `
                <td>
                    <select name="prods_id[]" class="form-select select-prod-edit" onchange="autoCompletarPrecioEdit(this)" required>
                        ${opcionesProductos}
                    </select>
                </td>
                <td>
                    <input type="number" name="prods_cant[]" class="form-control input-cant-edit" value="${d.cantidad}" min="1" step="1" oninput="calcularTotalesEdit()" required>
                </td>
                <td>
                    <input type="number" name="prods_precio[]" class="form-control input-precio-edit" value="${parseFloat(d.precioCompra).toFixed(2)}" step="0.01" min="0" oninput="calcularTotalesEdit()" required>
                </td>
                <td>
                    <input type="text" class="form-control bg-light input-subtotal-edit fw-bold text-dark" value="${parseFloat(d.MontoTotal).toFixed(2)}" readonly>
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
    calcularTotalesEdit();
}

function agregarFilaEdit() {
    let tbody = document.getElementById("e_tabla-body");
    let fila = document.createElement("tr");
    
    fila.innerHTML = `
        <td>
            <select name="prods_id[]" class="form-select select-prod-edit" onchange="autoCompletarPrecioEdit(this)" required>
                ${opcionesProductos}
            </select>
        </td>
        <td>
            <input type="number" name="prods_cant[]" class="form-control input-cant-edit" value="1" min="1" step="1" oninput="calcularTotalesEdit()" required>
        </td>
        <td>
            <input type="number" name="prods_precio[]" class="form-control input-precio-edit" step="0.01" min="0" placeholder="0.00" oninput="calcularTotalesEdit()" required>
        </td>
        <td>
            <input type="text" class="form-control bg-light input-subtotal-edit fw-bold text-dark" value="0.00" readonly>
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
        calcularTotalesEdit();
    } else {
        alert("Debe incluir al menos un producto.");
    }
}

function autoCompletarPrecioEdit(select) {
    let option = select.options[select.selectedIndex];
    let precio = option.getAttribute('data-precio') || '';
    let fila = select.closest('tr');
    let inputPrecio = fila.querySelector('.input-precio-edit');
    
    if (precio) {
        inputPrecio.value = parseFloat(precio).toFixed(2);
    }
    calcularTotalesEdit();
}

function calcularTotalesEdit() {
    let filas = document.querySelectorAll('#e_tabla-body tr');
    let totalGeneral = 0;

    filas.forEach(fila => {
        let cant = parseFloat(fila.querySelector('.input-cant-edit').value) || 0;
        let precio = parseFloat(fila.querySelector('.input-precio-edit').value) || 0;
        let subtotal = cant * precio;

        fila.querySelector('.input-subtotal-edit').value = subtotal.toFixed(2);
        totalGeneral += subtotal;
    });

    document.getElementById('e_totalGeneral').textContent = 'S/ ' + totalGeneral.toFixed(2);
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