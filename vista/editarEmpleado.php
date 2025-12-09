<?php
require_once __DIR__ . '/../Controlador/empleadoControlador.php';
$control = new EmpleadoControlador();

$id = $_GET["id"] ?? $_POST["idEmpleado"] ?? null;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($control->actualizar(
        $_POST["idEmpleado"], $_POST["idPersona"], $_POST["nombres"], $_POST["apellidos"],
        $_POST["dni"], $_POST["telefono"], $_POST["email"], $_POST["tipo"], $_POST["idEstado"]
    )) {
        echo "<script>window.location.href='menu.php?page=empleados&success=updated';</script>"; exit;
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar.</div>";
    }
}


$e = ($id) ? $control->editar($id) : null;
if (!$e) { echo "<div class='alert alert-danger'>Empleado no encontrado.</div>"; return; }
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2 class="text-primary">Editar Empleado</h2>
        
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="idEmpleado" value="<?= $e['idEmpleado'] ?>">
                <input type="hidden" name="idPersona" value="<?= $e['idPersona'] ?>">
                
                <h5 class="text-muted mb-3">Datos Personales</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4"><label>Nombres</label><input type="text" name="nombres" class="form-control" value="<?= htmlspecialchars($e['nombres']) ?>" required></div>
                    <div class="col-md-4"><label>Apellidos</label><input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($e['apellidos']) ?>" required></div>
                    <div class="col-md-2"><label>DNI</label><input type="text" name="dni" class="form-control" value="<?= htmlspecialchars($e['dni']) ?>" required></div>
                    <div class="col-md-2"><label>Teléfono</label><input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($e['telefono']) ?>"></div>
                    <div class="col-md-4"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($e['email']) ?>"></div>
                </div>

                <h5 class="text-muted mb-3">Datos Laborales</h5>
                <div class="row g-3">
                    <div class="col-md-4"><label>Cargo / Tipo</label><input type="text" name="tipo" class="form-control" value="<?= htmlspecialchars($e['tipo']) ?>" required></div>
                    <div class="col-md-4">
                        <label>Estado</label>
                        <select name="idEstado" class="form-select">
                            <option value="1" <?= $e['idEstado']==1?'selected':'' ?>>Activo</option>
                            <option value="0" <?= $e['idEstado']==0?'selected':'' ?>>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="menu.php?page=empleados" class="btn btn-light me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>