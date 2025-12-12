<?php
require_once("../Controlador/CategoriaControlador.php");

$control = new CategoriaControlador();

// BUSCAR
$texto = $_POST["buscar"] ?? "";
if (isset($_POST["buscar"])) {
    $categorias = $control->buscar($_POST["buscar"]);
} else {
    $categorias = $control->listar();
}

// COLUMNAS
$columnas = $control->obtenerColumnas();

// GUARDAR
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"])) {

    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $idEstado = $_POST["idEstado"];

    if ($control->guardar($nombre, $descripcion, $idEstado)) {
        header("Location: menu.php?page=categorias&success=1");
        exit();
    }
}
?>


<head>
    <title>Gestión de Categorías</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/menu.css">
</head>
<body>
  
       

<h1><b>Categorias</b></h1>

<input type="text" id="buscar" placeholder="Buscar producto..." autocomplete="off">
<br>



<br>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">Categoría guardada correctamente</p>
<?php endif; ?>

<div>


<form action="menu.php?page=categorias" method="POST">

    <label>Nombre:</label>
    <input type="text" name="nombre" required><br><br>

    <label>Descripción:</label>
    <textarea name="descripcion"></textarea><br><br>

    <label>ID Estado:</label>
    <input type="number" name="idEstado" required><br><br>

    <button type="submit">Agregar</button>

</form>
</div>

<div>

<table id="tablaProductos" border="1">
    <tr>
        <?php foreach ($columnas as $col): ?>
            <th><?= $col ?></th>
        <?php endforeach; ?>
    </tr>

    <?php foreach ($categorias as $c): ?>
        <tr>
            <td>
                
                <a href="menu.php?page=editar_categorias&id=<?= $c['idCategoria'] ?>">
                    <?= $c['idCategoria'] ?>
                </a>
            </td>

            <?php foreach (array_slice($columnas, 1) as $col): ?>
                <td><?= $c[$col] ?></td>
            <?php endforeach; ?>

        </tr>
    <?php endforeach; ?>
</table>
</div>

<script>
let input = document.getElementById("buscar");
let tabla = document.getElementById("tablaProductos");

input.onkeyup = function() {
    let texto = input.value.toLowerCase();
    let filas = tabla.getElementsByTagName("tr");

    for (let i = 1; i < filas.length; i++) { 
        let filaTexto = filas[i].innerText.toLowerCase();
        
        if (filaTexto.indexOf(texto) > -1) {
            filas[i].display = "";
        } else {
            filas[i].style.display = "none";
        }
    }
};
</script>


</body>

