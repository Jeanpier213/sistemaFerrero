<?php
require_once("../Controlador/proveedorControlador.php");

$control = new ProveedorControlador();

// SI ENVÍA FORMULARIO
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idProv = $_POST['idProveedor'];
    $idPers = $_POST['idPersona'];
    
    // Datos Contacto
    $nom = $_POST['nombres'];
    $ape = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $tel = $_POST['telefono'];
    $email = $_POST['email'];
    
    // Datos Empresa
    $razon = $_POST['razonSocial'];
    $ruc = $_POST['ruc'];
    $dir = $_POST['direccion'];
    $tipoProd = $_POST['tipoProductos'];
    
    $est = $_POST['idEstado'];

    if ($control->actualizar($idProv, $idPers, $nom, $ape, $dni, $tel, $email, $razon, $ruc, $dir, $tipoProd, $est)) {
        echo "<script>window.location.href='menu.php?page=proveedor&success=1';</script>"; 
        exit;
    }
}

// SI ES GET
$p = $control->editar($_GET["id"]);
?>

<h2>Editar Proveedor</h2>

<form action="menu.php?page=editar_proveedor" method="POST">

    <input type="hidden" name="idProveedor" value="<?= $p['idProveedor'] ?>">
    <input type="hidden" name="idPersona" value="<?= $p['idPersona'] ?>">

    <h3>Datos Empresa</h3>

    <label>Razón Social:</label>
    <input type="text" name="razonSocial" value="<?= $p['razonSocial'] ?>" required><br><br>

    <label>RUC:</label>
    <input type="text" name="ruc" value="<?= $p['ruc'] ?>" required><br><br>

    <label>Dirección:</label>
    <input type="text" name="direccion" value="<?= $p['direccion'] ?>"><br><br>

    <label>Rubro:</label>
    <input type="text" name="tipoProductos" value="<?= $p['tipoProductos'] ?>"><br><br>

    <h3>Datos Contacto</h3>

    <label>Nombres:</label>
    <input type="text" name="nombres" value="<?= $p['nombres'] ?>" required><br><br>

    <label>Apellidos:</label>
    <input type="text" name="apellidos" value="<?= $p['apellidos'] ?>" required><br><br>

    <label>Teléfono:</label>
    <input type="text" name="telefono" value="<?= $p['telefono'] ?>"><br><br>

    <label>Estado:</label>
    <select name="idEstado">
        <option value="1" <?= $p['idEstado'] == 1 ? 'selected' : '' ?>>Activo</option>
        <option value="0" <?= $p['idEstado'] == 0 ? 'selected' : '' ?>>Inactivo</option>
    </select>
    <br><br>

    <input type="hidden" name="dni" value="<?= $p['dni'] ?>">
    <input type="hidden" name="email" value="<?= $p['email'] ?>">

    <button type="submit">Actualizar</button>

</form>