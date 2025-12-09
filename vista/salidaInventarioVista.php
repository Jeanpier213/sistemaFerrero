                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <?php
require_once("../Controlador/salidaControlador.php");
require_once("../Controlador/productoControlador.php");
require_once("../Controlador/empleadoControlador.php");

// instancias
$salidaCtrl = new salidaControlador();
$productoCtrl = new productoControlador();
$empleadoCtrl = new empleadoControlador();

// datos
$columnasSalida = $salidaCtrl->obtenerColumnasSalida();
$columnasDetalle = $salidaCtrl->obtenerColumnasDetalle();
$productos = $productoCtrl->listar();   // debes tener método listar()
$empleados = $empleadoCtrl->listar();   // idem
$salidas = $salidaCtrl->listarSalidasConDetalle();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro de Salidas</title>
<style>
    body { font-family: Arial; max-width: 1200px; margin: auto; }
    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
    th, td { border: 1px solid #333; padding: 6px; text-align: center; }
    h2 { background: #222; color: #fff; padding: 6px; }
    input, select { padding: 6px; margin: 4px; }
    .btn { padding: 8px 14px; background: green; color: #fff; border: none; cursor: pointer; }
</style>
</head>

<body>

<h2>Registrar Nueva Salida</h2>

<form action="../Controlador/salidaControlador.php" method="POST">

    Fecha: <input type="date" name="fecha" required><br>

    Empleado:
    <select name="idTrabajador" required>
        <option value="">Seleccione</option>
        <?php foreach ($empleados as $e): ?>
            <option value="<?= $e['idEmpleado'] ?>">
                <?= $e['nombres'] . " " . $e['apellidos'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br>

    Motivo:
    <select name="motivo">
        <option value="Venta">Venta</option>
        <option value="Robo">Robo</option>
        <option value="Pérdida">Pérdida</option>
    </select>

    <h3>Agregar Detalles</h3>

    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
        </tr>

        <!-- 3 filas para agregar detalles -->
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

    <button class="btn">Registrar Salida</button>
</form>


<br><br>

<h2>Listado Completo de Salidas</h2>

<?php 
unset($columnasSalida[0]); // elimina la segunda columna
unset($columnasDetalle[1]); // elimina la segunda columna
unset($columnasDetalle[0]); // elimina la segunda columna

?>
<table>
    <tr>
        <!-- columnas automáticas -->
        <?php foreach ($columnasSalida as $col): ?>
            <th><?= $col ?></th>
        <?php endforeach; ?>
        <?php foreach ($columnasDetalle as $col): ?>
            <th><?= $col ?></th>
        <?php endforeach; ?>
    </tr>

   <?php for ($i = 0; $i < count($salidas); $i++): ?>
<tr>

    <td><?= $salidas[$i]['fecha'] ?></td>

    <td><?= $salidas[$i]['idEmpleado'] ?></td>
        <td><?= $salidas[$i]['motivo'] ?></td>
    <td><?= $salidas[$i]['idProducto'] ?></td>
    <td><?= $salidas[$i]['cantidadSalida'] ?></td>
</tr>
<?php endfor; ?>
</table>

</body>
</html>
