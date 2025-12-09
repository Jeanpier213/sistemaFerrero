<?php
require_once __DIR__ . '/../Controlador/ProductoControlador.php';
$control = new ProductoControlador();

// ACCIONES (Guardar, Estado)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["accion"]) && $_POST["accion"] === 'guardar') {
        $control->guardar($_POST["nombre"], $_POST["unidadMedida"], $_POST["tipo"], $_POST["cantidadTotal"], $_POST["fechaCaducidad"], $_POST["precioReferencial"], $_POST["precioVenta"], $_POST["umbralMinimo"], $_POST["idCategoria"], 1);
        echo "<script>window.location.href='menu.php?page=productos&success=1';</script>"; exit;
    }
    if (isset($_POST["accion"]) && $_POST["accion"] === 'estado') {
        $control->cambiarEstado($_POST['idProducto'], $_POST['nuevo_estado']);
        echo "<script>window.location.href='menu.php?page=productos';</script>"; exit;
    }
}

// BUSCAR / LISTAR
$texto = $_POST["buscar"] ?? "";
$productos = isset($_POST["buscar"]) ? $control->buscar($texto) : $control->listar();
?>

<h2>Gestión de Productos</h2>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success p-2">Producto guardado correctamente</div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="menu.php?page=productos" class="form-inline">
        <input type="hidden" name="accion" value="guardar">
        
        <div class="form-group"><label>Nombre</label><input name="nombre" class="form-control" required></div>
        <div class="form-group"><label>Unidad</label><input name="unidadMedida" class="form-control" size="5"></div>
        <div class="form-group"><label>Tipo</label><input name="tipo" class="form-control" size="10"></div>
        <div class="form-group"><label>Stock</label><input type="number" name="cantidadTotal" class="form-control" style="width: 80px;"></div>
        <div class="form-group"><label>Precio</label><input type="number" step="0.01" name="precioVenta" class="form-control" style="width: 100px;" required></div>
        <div class="form-group"><label>ID Cat</label><input type="number" name="idCategoria" class="form-control" style="width: 60px;" required></div>
        <div class="form-group"><label>Fecha</label><input type="date" name="fechaCaducidad" class="form-control"></div>
        
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
</div>

<input type="text" id="buscar" placeholder="🔍 Buscar producto..." class="form-control mb-3" style="width: 300px;">

<table id="tablaProductos">
    <thead>
        <tr><th>ID</th><th>Producto</th><th>Tipo</th><th>Stock</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
    <?php foreach ($productos as $p): $activo = $p['idEstado'] == 1; ?>
        <tr>
            <td><?= $p['idProducto'] ?></td>
            <td><?= $p['nombre'] ?></td>
            <td><?= $p['tipo'] ?></td>
            <td><?= $p['cantidadTotal'] ?></td>
            <td>S/ <?= $p['precioVenta'] ?></td>
            <td><?= $activo ? 'Activo' : 'Inactivo' ?></td>
            <td>
                <a href="menu.php?page=editar_producto&id=<?= $p['idProducto'] ?>" class="btn btn-warning btn-sm">Editar</a>
                
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="accion" value="estado">
                    <input type="hidden" name="idProducto" value="<?= $p['idProducto'] ?>">
                    <input type="hidden" name="nuevo_estado" value="<?= $activo ? 0 : 1 ?>">
                    <button class="btn btn-<?= $activo?'danger':'success' ?> btn-sm"><?= $activo?'Desactivar':'Activar' ?></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
document.getElementById("buscar").onkeyup = function() {
    let val = this.value.toLowerCase();
    let filas = document.getElementById("tablaProductos").getElementsByTagName("tr");
    for(let i=1; i<filas.length; i++) {
        if(filas[i].cells.length > 1) filas[i].style.display = filas[i].innerText.toLowerCase().includes(val) ? "" : "none";
    }
}
</script>