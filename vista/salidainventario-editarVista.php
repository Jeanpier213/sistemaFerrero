<?php
require_once("../Controlador/salidaControlador.php");
require_once("../Controlador/productoControlador.php");
require_once("../Controlador/empleadoControlador.php");

// instancias
$salidaCtrl = new salidaControlador();
$productoCtrl = new productoControlador();

// obtener id
$idSalida = $_GET["id"];

// obtener la salida + detalles
$salida = $salidaCtrl->obtenerSalidaPorId($idSalida);

// validar
if (!$salida) {
    die("Salida no encontrada");
}

// productos disponibles
$productos = $productoCtrl->listar();

// datos generales (todos los registros traen lo mismo)
$motivoActual = $salida[0]["motivo"];
$fecha = $salida[0]["fecha"];
$idTrabajador = $salida[0]["idTrabajador"];


?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Salida</title>

</head>

<body>

<h2>Editar Salida #<?= $idSalida ?></h2>

<form action="../Controlador/salidaControlador.php" method="POST">

    <input type="hidden" name="accion" value="editar">
    <input type="hidden" name="idSalida" value="<?= $idSalida ?>">

    Fecha:  
    <input type="date"  name="fecha" value="<?= $fecha ?>" disabled><br><br>

    Trabajador:  
    <input type="text"  name="idTrabajador" value="<?= $idTrabajador ?>" disabled><br><br>

    Motivo:
    <select name="motivo" required>
        <option value="Venta"   <?= $motivoActual=="Venta"?"selected":"" ?>>Venta</option>
        <option value="Robo"    <?= $motivoActual=="Robo"?"selected":"" ?>>Robo</option>
        <option value="Pérdida" <?= $motivoActual=="Pérdida"?"selected":"" ?>>Pérdida</option>
    </select>

    <h3>Editar Detalles</h3>

    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
        </tr>

        <?php foreach ($salida as $i => $det): ?>
        <tr>
            <td>
                <select name="detalle[<?= $i ?>][idProducto]" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($productos as $p): ?>
                        <option value="<?= $p['idProducto'] ?>"
                            <?= $p['idProducto']==$det['idProducto'] ? "selected" : "" ?>>
                            <?= $p['nombre'] ?> (Stock: <?= $p['cantidadTotal'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>

            <td>
                <input type="number" min="1" required
                       name="detalle[<?= $i ?>][cantidad]"
                       value="<?= $det['cantidad'] ?>">
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <button class="btn">Guardar Cambios</button>
</form>

</body>
</html>
