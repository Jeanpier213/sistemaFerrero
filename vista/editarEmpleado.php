<?php
require_once("../Controlador/empleadoControlador.php");

$control = new EmpleadoControlador();


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idEmpleado = $_POST["idEmpleado"];
    $idPersona = $_POST["idPersona"];
    
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $tipo = $_POST["tipo"]; 
    $idEstado = $_POST["idEstado"];

    if ($control->actualizar($idEmpleado, $idPersona, $nombres, $apellidos, $dni, $telefono, $email, $tipo, $idEstado)) {
        header("Location: menu.php?page=empleados&success=1");
        exit;

    }
}


$e = $control->editar($_GET["id"]);
?>

<h2>Editar Empleado</h2>

<form action="menu.php?page=editar_empleado" method="POST">

    <input type="hidden" name="idEmpleado" value="<?= $e['idEmpleado'] ?>">
    <input type="hidden" name="idPersona" value="<?= $e['idPersona'] ?>">

    <label>Nombres:</label>
    <input type="text" name="nombres" value="<?= $e['nombres'] ?>" required><br><br>

    <label>Apellidos:</label>
    <input type="text" name="apellidos" value="<?= $e['apellidos'] ?>" required><br><br>

    <label>DNI:</label>
    <input type="text" name="dni" value="<?= $e['dni'] ?>" required><br><br>

    <label>Teléfono:</label>
    <input type="text" name="telefono" value="<?= $e['telefono'] ?>"><br><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= $e['email'] ?>"><br><br>

    <label>Cargo:</label>
    <input type="text" name="tipo" value="<?= $e['tipo'] ?>" required><br><br>

    <label>Estado:</label>
    <select name="idEstado">
        <option value="1" <?= $e['idEstado'] == 1 ? 'selected' : '' ?>>Activo</option>
        <option value="0" <?= $e['idEstado'] == 0 ? 'selected' : '' ?>>Inactivo</option>
    </select>
    <br><br>

    <button type="submit">Actualizar</button>

</form>