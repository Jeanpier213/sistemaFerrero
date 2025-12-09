<?php
require_once __DIR__ . '/../Controlador/empleadoControlador.php';
$control = new EmpleadoControlador();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["accion"]) && $_POST["accion"] === 'guardar') {
        $control->guardar($_POST['nombres'], $_POST['apellidos'], $_POST['dni'], $_POST['telefono'], $_POST['email'], $_POST['tipo']);
        echo "<script>window.location.href='menu.php?page=empleados&success=1';</script>"; exit;
    }
    if (isset($_POST["accion"]) && $_POST["accion"] === 'estado') {
        $control->cambiarEstado($_POST['idEmpleado'], $_POST['nuevo_estado']);
        echo "<script>window.location.href='menu.php?page=empleados';</script>"; exit;
    }
}
$texto = $_POST["buscar"] ?? "";
$lista = isset($_POST["buscar"]) ? $control->buscar($texto) : $control->listar();
?>

<h2>Gestión de Empleados</h2>
<?php if (isset($_GET['success'])): ?><p style="color: green;">Empleado guardado</p><?php endif; ?>

<div class="form-container">
    <form method="POST" action="menu.php?page=empleados" class="form-inline">
        <input type="hidden" name="accion" value="guardar">
        <div class="form-group"><label>Nombres</label><input name="nombres" class="form-control" required></div>
        <div class="form-group"><label>Apellidos</label><input name="apellidos" class="form-control" required></div>
        <div class="form-group"><label>DNI</label><input name="dni" class="form-control" maxlength="8" required></div>
        <div class="form-group"><label>Teléfono</label><input name="telefono" class="form-control"></div>
        <div class="form-group"><label>Cargo</label><input name="tipo" class="form-control" required></div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>

<input type="text" id="buscar" placeholder="🔍 Buscar empleado..." class="form-control mb-3" style="width: 300px;">

<table id="tablaEmpleados">
    <thead><tr><th>ID</th><th>Nombre</th><th>DNI</th><th>Cargo</th><th>Estado</th><th>Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($lista as $e): $activo = $e['idEstado'] == 1; ?>
        <tr>
            <td><?= $e['idEmpleado'] ?></td>
            <td><?= $e['nombres'] . " " . $e['apellidos'] ?></td>
            <td><?= $e['dni'] ?></td>
            <td><?= $e['tipo'] ?></td>
            <td><?= $activo ? 'Activo' : 'Inactivo' ?></td>
            <td>
                <a href="menu.php?page=editar_empleado&id=<?= $e['idEmpleado'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="accion" value="estado">
                    <input type="hidden" name="idEmpleado" value="<?= $e['idEmpleado'] ?>">
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
    let rows = document.getElementById("tablaEmpleados").getElementsByTagName("tr");
    for(let i=1; i<rows.length; i++) {
        if(rows[i].cells.length > 1) rows[i].style.display = rows[i].innerText.toLowerCase().includes(val) ? "" : "none";
    }
}
</script>