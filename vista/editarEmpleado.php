<?php
require_once __DIR__ . '/../Controlador/empleadoControlador.php';
$control = new EmpleadoControlador();
$id = $_GET["id"] ?? $_POST["idEmpleado"] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($control->actualizar($_POST["idEmpleado"], $_POST["idPersona"], $_POST["nombres"], $_POST["apellidos"], $_POST["dni"], $_POST["telefono"], $_POST["email"], $_POST["tipo"], $_POST["idEstado"])) {
        echo "<script>window.location.href='menu.php?page=empleados&success=edit';</script>"; exit;
    }
}
$e = ($id) ? $control->editar($id) : null;
if (!$e) { echo "Empleado no encontrado."; return; }
?>

<h2>Editar Empleado</h2>
<a href="menu.php?page=empleados" class="btn btn-secondary mb-3">Volver</a>

<div class="form-container" style="max-width: 600px;">
    <form method="POST">
        <input type="hidden" name="idEmpleado" value="<?= $e['idEmpleado'] ?>">
        <input type="hidden" name="idPersona" value="<?= $e['idPersona'] ?>">
        
        <div class="form-inline mb-2">
            <div class="form-group"><label>Nombres</label><input name="nombres" class="form-control" value="<?= $e['nombres'] ?>" required></div>
            <div class="form-group"><label>Apellidos</label><input name="apellidos" class="form-control" value="<?= $e['apellidos'] ?>" required></div>
        </div>
        <div class="form-inline mb-2">
            <div class="form-group"><label>DNI</label><input name="dni" class="form-control" value="<?= $e['dni'] ?>" required></div>
            <div class="form-group"><label>Teléfono</label><input name="telefono" class="form-control" value="<?= $e['telefono'] ?>"></div>
        </div>
        <div class="form-group mb-2"><label>Email</label><input name="email" class="form-control" value="<?= $e['email'] ?>"></div>
        <div class="form-inline mb-2">
            <div class="form-group"><label>Cargo</label><input name="tipo" class="form-control" value="<?= $e['tipo'] ?>" required></div>
            <div class="form-group"><label>Estado (1=Act, 0=Inac)</label><input type="number" name="idEstado" class="form-control" value="<?= $e['idEstado'] ?>" required></div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
    </form>
</div>