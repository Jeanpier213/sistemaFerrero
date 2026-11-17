<?php
require_once '../Modelo/Producto.php';
require_once '../Modelo/Cliente.php';

$productoModelo = new Producto();
$clienteModelo = new Cliente();

$productos = $productoModelo->listar() ?? [];
$clientes = $clienteModelo->listarTodos() ?? []; // Ajustado al nombre de la función en Cliente.php
?>
<style>
    .pos-cart-table th { background-color: #f8f9fa; font-size: 0.85rem; }
    .total-box { background: #212529; color: #0dfd8e; border-radius: 12px; }
    .catalog-container { max-height: 60vh; overflow-y: auto; }
    .product-row:hover { background-color: #f1f3f5; cursor: pointer; transition: 0.2s; }
</style>

<div class="container-fluid pt-4 px-4 pb-5">
    
    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                <i class="bi bi-shop fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Punto de Venta</h4>
                <span class="text-muted small">Caja, facturación y ventas rápidas</span>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- PANEL IZQUIERDO: Catálogo de Productos -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-box-seam text-primary me-2"></i> Productos</h5>
                        <div class="input-group w-50">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="buscadorProductos" class="form-control border-start-0 ps-0" placeholder="Buscar por nombre..." onkeyup="filtrarProductos()">
                        </div>
                    </div>

                    <div class="table-responsive catalog-container border rounded-3">
                        <table class="table table-hover align-middle mb-0" id="tablaCatalogo">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($productos as $p): ?>
                                    <?php if($p['idEstado'] == 1 && $p['cantidadTotal'] > 0): ?>
                                    <tr class="product-row">
                                        <td class="fw-medium name-col"><?= htmlspecialchars($p['nombre']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-<?= $p['cantidadTotal'] > 10 ? 'success' : 'warning text-dark' ?>">
                                                <?= $p['cantidadTotal'] ?>
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-primary">S/ <?= number_format($p['precioVenta'], 2) ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" 
                                                    onclick="agregarAlCarrito(<?= $p['idProducto'] ?>, '<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>', <?= $p['precioVenta'] ?>, <?= $p['cantidadTotal'] ?>)">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- PANEL DERECHO: Carrito y Pago -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-3 d-flex flex-column">
                    <h5 class="fw-bold text-secondary border-bottom pb-2 mb-3"><i class="bi bi-cart3"></i> Carrito de Compras</h5>
                    
                    <!-- Tabla del Carrito -->
                    <div class="table-responsive flex-grow-1 border rounded-3 mb-3" style="max-height: 250px; overflow-y: auto;">
                        <table class="table align-middle pos-cart-table mb-0">
                            <thead class="sticky-top">
                                <tr>
                                    <th>Producto</th>
                                    <th width="90" class="text-center">Cant.</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="tablaCarrito">
                                <tr><td colspan="4" class="text-center text-muted py-4"><i class="bi bi-cart-x fs-1 d-block opacity-25"></i> Vacío</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Controles de Pago y Cliente -->
                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <!-- SELECTOR DE CLIENTE CON BOTÓN "+" -->
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                <select id="idCliente" class="form-select">
                                    <option value="5">Seleccione un cliente</option>
                                    <?php foreach($clientes as $c): ?>
                                        <?php if(isset($c['idCliente']) && $c['idCliente'] != 1): ?>
                                            <option value="<?= $c['idCliente'] ?>"><?= htmlspecialchars($c['nombres']) ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#modalClienteCaja" title="Agregar cliente rápido">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-6">
                            <select id="tipoComprobante" class="form-select form-select-sm">
                                <option value="Boleta">Boleta</option>
                                <option value="Factura">Factura</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <select id="metodoPago" class="form-select form-select-sm">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Yape/Plin">Yape/Plin</option>
                                <option value="Tarjeta">Tarjeta</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Total y Botones -->
                    <div class="total-box p-3 mb-3 text-center shadow-sm">
                        <span class="d-block text-white-50 small fw-bold">TOTAL A COBRAR</span>
                        <h2 class="fw-bold mb-0">S/ <span id="lblTotal">0.00</span></h2>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary w-50 fw-bold" onclick="generarProforma()">
                            <i class="bi bi-file-text"></i> Proforma
                        </button>
                        <button class="btn btn-success w-50 fw-bold shadow-sm" onclick="procesarVenta()">
                            <i class="bi bi-check-circle"></i> VENDER
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cliente Rápido en Caja -->
<div class="modal fade" id="modalClienteCaja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title fw-bold"><i class="bi bi-person-plus"></i> Nuevo Cliente Rápido</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formClienteCaja">
                    <div class="input-group mb-3">
                        <select class="form-select" id="pos_tipoDoc" style="max-width: 100px;">
                            <option value="DNI">DNI</option>
                            <option value="RUC">RUC</option>
                        </select>
                        <input type="number" class="form-control" id="pos_numDoc" placeholder="Número Documento" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="pos_nombres" placeholder="Nombres Completos / Razón Social" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="pos_direccion" placeholder="Dirección (Opcional)">
                    </div>
                    <div class="mb-0">
                        <input type="text" class="form-control" id="pos_telefono" placeholder="Teléfono (Opcional)">
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success fw-bold" onclick="guardarClienteCaja()">Guardar y Seleccionar</button>
            </div>
        </div>
    </div>
</div>

<script>
    let carrito = [];

    // Buscador en tiempo real para el catálogo
    function filtrarProductos() {
        const input = document.getElementById("buscadorProductos").value.toLowerCase();
        const filas = document.querySelectorAll("#tablaCatalogo tbody tr");
        
        filas.forEach(fila => {
            const nombre = fila.querySelector(".name-col").textContent.toLowerCase();
            fila.style.display = nombre.includes(input) ? "" : "none";
        });
    }

    function agregarAlCarrito(idProducto, nombre, precio, stockMax) {
        let itemExistente = carrito.find(item => item.idProducto == idProducto);
        
        if (itemExistente) {
            if(itemExistente.cantidad + 1 > stockMax) {
                alert(`Stock insuficiente. Quedan ${stockMax} unidades.`);
                return;
            }
            itemExistente.cantidad++;
            itemExistente.subtotal = itemExistente.cantidad * itemExistente.precio;
        } else {
            carrito.push({ idProducto, nombre, precio, cantidad: 1, subtotal: precio, stockMax });
        }
        renderizarCarrito();
    }

    function renderizarCarrito() {
        const tbody = document.getElementById('tablaCarrito');
        tbody.innerHTML = '';
        let total = 0;

        if (carrito.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4"><i class="bi bi-cart-x fs-1 d-block opacity-25"></i> Vacío</td></tr>';
            document.getElementById('lblTotal').innerText = "0.00";
            return;
        }

        carrito.forEach((item, index) => {
            total += item.subtotal;
            tbody.innerHTML += `
                <tr>
                    <td class="text-truncate" style="max-width: 120px;" title="${item.nombre}"><small>${item.nombre}</small></td>
                    <td>
                        <input type="number" class="form-control form-control-sm text-center" value="${item.cantidad}" min="1" max="${item.stockMax}" onchange="cambiarCantidad(${index}, this.value)">
                    </td>
                    <td class="text-end fw-bold">S/ ${item.subtotal.toFixed(2)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm text-danger border-0" onclick="eliminarDelCarrito(${index})"><i class="bi bi-x-circle-fill"></i></button>
                    </td>
                </tr>
            `;
        });
        document.getElementById('lblTotal').innerText = total.toFixed(2);
    }

    function cambiarCantidad(index, nuevaCantidad) {
        let cantidad = parseInt(nuevaCantidad) || 1;
        if (cantidad > carrito[index].stockMax) cantidad = carrito[index].stockMax;
        if (cantidad < 1) cantidad = 1;
        
        carrito[index].cantidad = cantidad;
        carrito[index].subtotal = cantidad * carrito[index].precio;
        renderizarCarrito();
    }

    function eliminarDelCarrito(index) {
        carrito.splice(index, 1);
        renderizarCarrito();
    }

    function recopilarDatos() {
        return {
            idCliente: document.getElementById('idCliente').value,
            tipoComprobante: document.getElementById('tipoComprobante').value,
            metodoPago: document.getElementById('metodoPago').value,
            total: document.getElementById('lblTotal').innerText,
            carrito: carrito
        };
    }

    function procesarVenta() {
        if (carrito.length === 0) return alert("El carrito está vacío.");
        fetch('../Controlador/VentaControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(recopilarDatos())
        }).then(res => res.json()).then(datos => {
            if (datos.status === 'success') {
                window.open('../Controlador/generar_ticket.php?id=' + datos.idVenta, '_blank');
                carrito = []; renderizarCarrito();
            } else alert("Error: " + datos.mensaje);
        }).catch(err => alert("Error de conexión al procesar venta."));
    }

    function generarProforma() {
        if (carrito.length === 0) return alert("El carrito está vacío.");
        fetch('../Controlador/CotizacionControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(recopilarDatos())
        }).then(res => res.json()).then(datos => {
            if (datos.status === 'success') {
                alert("Cotización generada correctamente.");
                carrito = []; renderizarCarrito();
            } else alert("Error: " + datos.mensaje);
        });
    }

    // ----------------------------------------------------
    // NUEVA FUNCIÓN: Guardar cliente mediante Ajax desde el POS
    // ----------------------------------------------------
    function guardarClienteCaja() {
        const data = {
            tipoDoc: document.getElementById('pos_tipoDoc').value,
            numDoc: document.getElementById('pos_numDoc').value,
            nombres: document.getElementById('pos_nombres').value,
            direccion: document.getElementById('pos_direccion').value,
            telefono: document.getElementById('pos_telefono').value
        };

        if (!data.numDoc || !data.nombres) {
            return alert("Por favor, ingrese al menos el documento y el nombre del cliente.");
        }

        fetch('../Controlador/ClienteControlador.php?op=guardarAjax', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                // 1. Agregar el nuevo cliente al combobox
                const select = document.getElementById('idCliente');
                const option = document.createElement('option');
                option.value = res.idCliente;
                option.text = data.nombres;
                select.appendChild(option);
                
                // 2. Dejarlo seleccionado automáticamente
                select.value = res.idCliente; 
                
                // 3. Cerrar el modal y limpiar el formulario
                bootstrap.Modal.getInstance(document.getElementById('modalClienteCaja')).hide();
                document.getElementById('formClienteCaja').reset();
                
                // Opcional: mensaje de éxito temporal
                alert("Cliente registrado y seleccionado con éxito.");
            } else {
                alert("Hubo un error: " + res.mensaje);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error de conexión al guardar el cliente.");
        });
    }
</script>