<?php
require_once __DIR__ . '/../Controlador/ProveedorControlador.php';
$control = new ProveedorControlador();

if (isset($_POST['accion'])) {
    if ($_POST['accion'] === 'guardar') {
        $control->guardar($_POST['nombres'], $_POST['apellidos'], $_POST['dni'], $_POST['telefono'], $_POST['email'], $_POST['razonSocial'], $_POST['ruc'], $_POST['direccion'], $_POST['tipoProductos']);
        echo "<script>window.location.href='menu.php?page=proveedores&success=1';</script>"; exit;
    }
    if ($_POST['accion'] === 'estado') {
        $control->cambiarEstado($_POST['idProveedor'], $_POST['nuevo_estado']);
        echo "<script>window.location.href='menu.php?page=proveedores';</script>"; exit;
    }
}
$texto = $_POST["buscar"] ?? "";
$lista = isset($_POST["buscar"]) ? $control->buscar($texto) : $control->listar();
?>

<h2 class="titulo-vista"><i class="bi bi-truck"></i> Gestión de Proveedores</h2>

<?php if (isset($_GET['success'])): ?><div class="alert alert-success p-2">Proveedor registrado.</div><?php endif; ?>

<div class="form-container">
    <h5 class="text-primary mb-3">Nuevo Proveedor</h5>
    <form method="POST" action="menu.php?page=proveedores">
        <input type="hidden" name="accion" value="guardar">
        
        <div class="form-inline mb-3">
            <div class="form-group"><label>Razón Social</label><input name="razonSocial" class="form-control" required></div>
            <div class="form-group"><label>RUC</label><input name="ruc" class="form-control" maxlength="11" required></div>
            <div class="form-group"><label>Dirección</label><input name="direccion" class="form-control"></div>
            <div class="form-group"><label>Tipo Producto</label><input name="tipoProductos" class="form-control"></div>
        </div>

        <div class="form-inline">
            <div class="form-group"><label>Nom. Contacto</label><input name="nombres" class="form-control" required></div>
            <div class="form-group"><label>Ape. Contacto</label><input name="apellidos" class="form-control" required></div>
            <div class="form-group"><label>Teléfono</label><input name="telefono" class="form-control"></div>
            <input type="hidden" name="email" value="">
            <input type="hidden" name="dni" value="">
            
            <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Guardar</button>
        </div>
    </form>
</div>

<input type="text" id="buscar" placeholder="🔍 Buscar por Empresa o RUC..." class="form-control mb-3" style="width: 350px;">

<table id="tablaProv">
    <thead>
        <tr><th>RUC</th><th>Empresa</th><th>Rubro</th><th>Contacto</th><th>Dirección</th><th>Estado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
    <?php foreach ($lista as $p): $activo = ($p['idEstado'] == 1); ?>
        <tr>
            <td><?= $p['ruc'] ?></td>
            <td style="font-weight: bold;"><?= $p['razonSocial'] ?></td>
            <td><?= $p['tipoProductos'] ?></td>
            <td><?= $p['nombres'] ?> (<?= $p['telefono'] ?>)</td>
            <td><?= $p['direccion'] ?></td>
            <td><?= $activo ? 'Activo' : 'Inactivo' ?></td>
            <td>
                <a href="menu.php?page=editar_proveedor&id=<?= $p['idProveedor'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="accion" value="estado">
                    <input type="hidden" name="idProveedor" value="<?= $p['idProveedor'] ?>">
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
    let rows = document.getElementById("tablaProv").getElementsByTagName("tr");
    for(let i=1; i<rows.length; i++) {
        if(rows[i].cells.length > 1) rows[i].style.display = rows[i].innerText.toLowerCase().includes(val) ? "" : "none";
    }
}
</script>