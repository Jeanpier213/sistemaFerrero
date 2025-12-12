<?php
require_once("../Controlador/salidaControlador.php");
require_once("../Controlador/productoControlador.php");
require_once("../Controlador/empleadoControlador.php");


$salidaCtrl = new salidaControlador();
$productoCtrl = new productoControlador();
$empleadoCtrl = new empleadoControlador();

$columnasSalida = $salidaCtrl->obtenerColumnasSalida();
$columnasDetalle = $salidaCtrl->obtenerColumnasDetalle();
$productos = $productoCtrl->listar();
$empleados = $empleadoCtrl->listar();
$salidas = $salidaCtrl->listarSalidasConDetalle();


if (isset($_POST['fecha'])) {
    $fecha        = $_POST['fecha'];
    $idTrabajador = $_POST['idTrabajador'];
    $motivo       = $_POST['motivo'];
    $detalles     = $_POST['detalle'];

    $ok = $salidaCtrl->agregar($fecha, $idTrabajador, $motivo, $detalles);

    if ($ok) {
        echo "<p>Salida registrada correctamente.</p>";
        
        $salidas = $salidaCtrl->listarSalidasConDetalle();
    } else {
        echo "<p>Error al registrar la salida.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro de Salidas</title>
</head>
<body>

<h2>Registrar Nueva Salida</h2>

<form action="" method="POST">

    Fecha: <input type="date" name="fecha" required><br>

    Empleado:
    <select name="idTrabajador" required>
        <option value="">Seleccione</option>
        <?php foreach ($empleados as $e): ?>
            <option value="<?= $e['idEmpleado'] ?>">
                <?= $e['nombres'] . " " . $e['apellidos'] ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    Motivo:
    <select name="motivo">
        <option value="Venta">Venta</option>
        <option value="Robo">Robo</option>
        <option value="Pérdida">Pérdida</option>
    </select>

    <h3>Agregar Detalles</h3>
    <table>
        <tr><th>Producto</th><th>Cantidad</th></tr>
        <?php for ($i = 0; $i < 10; $i++): ?>
            <tr>
                <td>
                    <select name="detalle[<?= $i ?>][idProducto]">
                        <option value="">Seleccione</option>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?= $p['idProducto'] ?>">
                                <?= $p['nombre'] ?> (Stock: <?= $p['cantidadTotal'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="number" min="1" name="detalle[<?= $i ?>][cantidad]" placeholder="0">
                </td>
            </tr>
        <?php endfor; ?>
    </table>

    <button type="submit">Registrar Salida</button>
</form>

<br><br>

<h2>Listado Completo de Salidas</h2>

<?php 
unset($columnasSalida[0]); 
unset($columnasDetalle[0]);
unset($columnasDetalle[1]);
?>

<table border="1">
    <tr>
        <?php foreach ($columnasSalida as $col): ?>
            <th><?= $col ?></th>
        <?php endforeach; ?>
        <?php foreach ($columnasDetalle as $col): ?>
            <th><?= $col ?></th>
        <?php endforeach; ?>
        <th>Acciones</th>
    </tr>

    <?php foreach ($salidas as $s): ?>
        <tr>
            <td><?= $s['fecha'] ?></td>
            <td><?= $s['idEmpleado'] ?></td>
            <td><?= $s['motivo'] ?></td>
            <td><?= $s['idProducto'] ?></td>
            <td><?= $s['cantidadSalida'] ?></td>
            <td>
                <a href="menu.php?page=editar_salidas&id=<?= $s['idSalida'] ?>">Editar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
