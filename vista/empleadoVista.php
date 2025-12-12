<?php
require_once("../Controlador/empleadoControlador.php");

$control = new EmpleadoControlador();

$lista = $control->listar();

// GUARDAR
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombres"])) {
    
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $tipo = $_POST["tipo"];

    if ($control->guardar($nombres, $apellidos, $dni, $telefono, $email, $tipo)) {
        header("Location: menu.php?page=empleados&success=1");
        exit;
        
    }
}
?>

<h1><b>Empleados</b></h1>

<input type="text" id="buscar" placeholder="Buscar empleado..." autocomplete="off">
<br><br>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Empleado guardado correctamente</p>
<?php endif; ?>

<div>
    <form action="menu.php?page=empleados" method="POST">
        
        <label>Nombres:</label>
        <input type="text" name="nombres" required><br><br>

        <label>Apellidos:</label>
        <input type="text" name="apellidos" required><br><br>

        <label>DNI:</label>
        <input type="text" name="dni" required><br><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono"><br><br>

        <label>Email:</label>
        <input type="email" name="email"><br><br>

        <label>Cargo:</label>
        <input type="text" name="tipo" required><br><br>

        <button type="submit">Agregar</button>
    </form>
</div>

<br>

<div>
    <table id="tablaEmpleados" border="1">
        <?php if (!empty($lista)): ?>
            
            <tr>
                <?php 
                $columnas = array_keys($lista[0]); 
                foreach ($columnas as $col): 
                    
                    if ($col != 'idEstado') { 
                ?>
                    <th><?= $col ?></th>
                <?php 
                    } 
                endforeach; 
                ?>
                
                <th>Estado</th>
            </tr>

            <?php foreach ($lista as $fila): ?>
                <tr>
                    
                    <td>
                        <a href="menu.php?page=editar_empleado&id=<?= $fila['idEmpleado'] ?>">
                            <?= $fila['idEmpleado'] ?>
                        </a>
                    </td>

                    <?php 
                        
                        foreach (array_slice($fila, 1, null, true) as $key => $valor): 
                            if ($key != 'idEstado'):
                    ?>
                        <td><?= $valor ?></td>
                    <?php 
                            endif;
                        endforeach; 
                    ?>

                    <td>
                        <?= ($fila['idEstado'] == 1) ? 'Activo' : 'Inactivo' ?>
                    </td>

                </tr>
            <?php endforeach; ?>
            
        <?php else: ?>
            <p>No hay empleados registrados.</p>
        <?php endif; ?>
    </table>
</div>

<script>
let input = document.getElementById("buscar");
let tabla = document.getElementById("tablaEmpleados");

input.onkeyup = function() {
    let texto = input.value.toLowerCase();
    let filas = tabla.getElementsByTagName("tr");

    for (let i = 1; i < filas.length; i++) { 
        let filaTexto = filas[i].innerText.toLowerCase();
        
        if (filaTexto.indexOf(texto) > -1) {
            filas[i].style.display = "";
        } else {
            filas[i].style.display = "none";
        }
    }
};
</script>