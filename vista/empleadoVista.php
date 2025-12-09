<?php
require_once __DIR__ . '/../Controlador/empleadoControlador.php';
$control = new EmpleadoControlador();

// LÓGICA: CAMBIAR ESTADO
if (isset($_POST['accion']) && $_POST['accion'] === 'cambiar_estado') {
    $control->cambiarEstado($_POST['idEmpleado'], $_POST['nuevo_estado']);
    echo "<script>window.location.href='menu.php?page=empleados';</script>"; exit;
}

// LÓGICA: GUARDAR
if (isset($_POST['accion']) && $_POST['accion'] === 'guardar') {
    if ($control->guardar($_POST['nombres'], $_POST['apellidos'], $_POST['dni'], $_POST['telefono'], $_POST['email'], $_POST['tipo'])) {
        echo "<script>window.location.href='menu.php?page=empleados&success=1';</script>"; exit;
    }
}

// LÓGICA: LISTAR
$texto = $_POST["buscar"] ?? "";
$lista = isset($_POST["buscar"]) ? $control->buscar($texto) : $control->listar();
?>

<div class="container-fluid">
    <h2 class="text-primary mb-4 border-bottom pb-2">Gestión de Empleados</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">Empleado registrado correctamente.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">Nuevo Empleado</div>
        <div class="card-body">
            <form method="POST" action="menu.php?page=empleados">
                <input type="hidden" name="accion" value="guardar">
                <div class="row g-2">
                    <div class="col-md-3"><input type="text" name="nombres" class="form-control" placeholder="Nombres" required></div>
                    <div class="col-md-3"><input type="text" name="apellidos" class="form-control" placeholder="Apellidos" required></div>
                    <div class="col-md-2"><input type="text" name="dni" class="form-control" placeholder="DNI" required maxlength="8"></div>
                    <div class="col-md-2"><input type="text" name="telefono" class="form-control" placeholder="Teléfono"></div>
                    <div class="col-md-2"><input type="email" name="email" class="form-control" placeholder="Email"></div>
                    
                    <div class="col-md-3"><input type="text" name="tipo" class="form-control" placeholder="Cargo (Ej. Vendedor)" required></div>
                    <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Guardar</button></div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4"><input type="text" id="buscar" class="form-control" placeholder="🔍 Buscar por nombre o DNI..."></div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table id="tablaEmpleados" class="table table-hover table-bordered mb-0">
                <thead class="table-dark text-center">
                    <tr><th>ID</th><th>Nombre Completo</th><th>DNI</th><th>Contacto</th><th>Cargo</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                <?php foreach ($lista as $e): $esActivo = ($e['idEstado'] == 1); ?>
                    <tr>
                        <td class="text-center fw-bold"><?= $e['idEmpleado'] ?></td>
                        <td><?= htmlspecialchars($e['nombres'] . " " . $e['apellidos']) ?></td>
                        <td><?= htmlspecialchars($e['dni']) ?></td>
                        <td><small><?= htmlspecialchars($e['telefono']) ?><br><?= htmlspecialchars($e['email']) ?></small></td>
                        <td><?= htmlspecialchars($e['tipo']) ?></td>
                        <td class="text-center"><span class="badge <?= $esActivo?'bg-success':'bg-secondary' ?>"><?= $esActivo?'Activo':'Inactivo' ?></span></td>
                        <td class="text-center">
                            
                            <a href="menu.php?page=editar_empleado&id=<?= $e['idEmpleado'] ?>" class="btn btn-outline-primary btn-sm">✏️</a>
                            
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="accion" value="cambiar_estado">
                                <input type="hidden" name="idEmpleado" value="<?= $e['idEmpleado'] ?>">
                                <input type="hidden" name="nuevo_estado" value="<?= $esActivo ? '0' : '1' ?>">
                                <button class="btn btn-outline-<?= $esActivo?'danger':'success' ?> btn-sm"><?= $esActivo?'🚫':'✅' ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
document.getElementById("buscar").onkeyup = function() {
    let val = this.value.toLowerCase();
    let filas = document.getElementById("tablaEmpleados").getElementsByTagName("tr");
    for(let i=1; i<filas.length; i++){
        if(filas[i].cells.length > 1) filas[i].style.display = filas[i].innerText.toLowerCase().includes(val) ? "" : "none";
    }
}
</script>