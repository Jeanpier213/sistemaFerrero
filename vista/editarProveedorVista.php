<?php
require_once __DIR__ . '/../Controlador/ProveedorControlador.php';
$control = new ProveedorControlador();
$id = $_GET["id"] ?? $_POST["idProveedor"] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($control->actualizar($_POST['idProveedor'], $_POST['idPersona'], $_POST['nombres'], $_POST['apellidos'], $_POST['dni'], $_POST['telefono'], $_POST['email'], $_POST['razonSocial'], $_POST['ruc'], $_POST['direccion'], $_POST['tipoProductos'], $_POST['idEstado'])) {
        echo "<script>window.location.href='menu.php?page=proveedores&success=updated';</script>"; exit;
    }
}
$p = ($id) ? $control->editar($id) : null;
if (!$p) { echo "Proveedor no encontrado."; return; }
?>

<h2>Editar Proveedor</h2>
<a href="menu.php?page=proveedores" class="btn btn-secondary mb-3">Volver</a>

<div class="form-container" style="max-width: 700px;">
    <form method="POST">
        <input type="hidden" name="idProveedor" value="<?= $p['idProveedor'] ?>">
        <input type="hidden" name="idPersona" value="<?= $p['idPersona'] ?>">

        <h5 class="text-muted">Empresa</h5>
        <div class="form-inline mb-3">
            <div class="form-group"><label>Razón Social</label><input name="razonSocial" class="form-control" value="<?= $p['razonSocial'] ?>" required></div>
            <div class="form-group"><label>RUC</label><input name="ruc" class="form-control" value="<?= $p['ruc'] ?>" required></div>
            <div class="form-group"><label>Tipo Producto</label><input name="tipoProductos" class="form-control" value="<?= $p['tipoProductos'] ?>"></div>
        </div>
        <div class="form-group mb-3"><label>Dirección</label><input name="direccion" class="form-control" value="<?= $p['direccion'] ?>"></div>

        <h5 class="text-muted">Contacto</h5>
        <div class="form-inline mb-3">
            <div class="form-group"><label>Nombres</label><input name="nombres" class="form-control" value="<?= $p['nombres'] ?>" required></div>
            <div class="form-group"><label>Apellidos</label><input name="apellidos" class="form-control" value="<?= $p['apellidos'] ?>" required></div>
            <div class="form-group"><label>Teléfono</label><input name="telefono" class="form-control" value="<?= $p['telefono'] ?>"></div>
        </div>

        <div class="form-inline">
             <div class="form-group"><label>Estado (1=Act, 0=Inac)</label><input type="number" name="idEstado" class="form-control" value="<?= $p['idEstado'] ?>" required></div>
             <input type="hidden" name="dni" value="<?= $p['dni'] ?>">
             <input type="hidden" name="email" value="<?= $p['email'] ?>">
             
             <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Actualizar</button>
        </div>
    </form>
</div>