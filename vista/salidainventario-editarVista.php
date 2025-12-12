<?php
require_once("../Controlador/salidaControlador.php");
require_once("../Controlador/productoControlador.php");


$salidaCtrl = new salidaControlador();
$productoCtrl = new productoControlador();


$idSalida = $_GET["id"] ?? null;




$salida = $salidaCtrl->obtenerSalidaPorId($idSalida);



$productos = $productoCtrl->listar();


$motivoActual = $salida[0]["motivo"];
$fecha = $salida[0]["fecha"];
$idTrabajador = $salida[0]["idTrabajador"];


if (isset($_POST['motivo'])) {
    $motivo   = $_POST['motivo'];
    $detalles = $_POST['detalle'];

    $ok = $salidaCtrl->editar($idSalida, $motivo, $detalles);

    if ($ok) {
        echo "<p>Salida editada correctamente.</p>";
        
        $salida = $salidaCtrl->obtenerSalidaPorId($idSalida);
        header("Location: menu.php?page=salidas&success=1");
        exit();
    } else {
        echo "<p>Error al editar la salida.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Salida</title>
</head>
<body>

<h2>Editar Salida #<?= $idSalida ?></h2>

<form action="" method="POST">

    Fecha:  
    <input type="date" name="fecha" value="<?= $fecha ?>" disabled><br><br>

    Trabajador:  
    <input type="text" name="idTrabajador" value="<?= $idTrabajador ?>" disabled><br><br>

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
                                <?= $p['idProducto'] == $det['idProducto'] ? "selected" : "" ?>>
                                <?= $p['nombre'] ?> (Stock: <?= $p['cantidadTotal'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="number" min="1" name="detalle[<?= $i ?>][cantidad]" 
                           value="<?= $det['cantidad'] ?>" required>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <button type="submit">Guardar Cambios</button>
</form>

</body>
</html>
