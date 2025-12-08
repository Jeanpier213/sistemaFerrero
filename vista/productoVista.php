<?php
require_once __DIR__ . '/../Controlador/productoControlador.php';

$control = new ProductoControlador();

// 1. LOGICA PARA CAMBIAR ESTADO (ACTIVAR / DESACTIVAR)
if (isset($_POST['accion']) && $_POST['accion'] === 'cambiar_estado') {
    $idProd = $_POST['idProducto'];
    $nuevoEstado = $_POST['nuevo_estado'];
    
    if ($control->cambiarEstado($idProd, $nuevoEstado)) {
        // Redirigimos para limpiar el POST y refrescar la tabla
        header("Location: productoVista.php?msg=estado_actualizado");
        exit;
    }
}

// 2. BUSCAR
$texto = $_POST["buscar"] ?? "";
if (isset($_POST["buscar"])) {
    $productos = $control->buscar($_POST["buscar"]);
} else {
    $productos = $control->listar();
}

// 3. COLUMNAS
$columnas = $control->obtenerColumnas();

// 4. GUARDAR NUEVO
if (isset($_POST["accion"]) && $_POST['accion'] === 'guardar') {
    if ($control->guardar(
        $_POST["nombre"], $_POST["unidadMedida"], $_POST["tipo"],
        $_POST["cantidadTotal"], $_POST["fechaCaducidad"], $_POST["precioReferencial"],
        $_POST["precioVenta"], $_POST["umbralMinimo"], $_POST["idCategoria"],
        $_POST["idEstado"]
    )) {
        header("Location: productoVista.php?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../style/menu.css">
</head>
<body>

<div class="container mt-4">
    <h1><b>Productos</b></h1>

    <input type="text" id="buscar" class="form-control mb-3" placeholder="Buscar producto..." autocomplete="off">

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Producto guardado correctamente</div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'estado_actualizado'): ?>
        <div class="alert alert-info">El estado del producto se ha actualizado.</div>
    <?php endif; ?>

    <div class="card p-3 mb-4">
        <h5>Nuevo Producto</h5>
        <form action="productoVista.php" method="POST" class="row g-3">
            <input type="hidden" name="accion" value="guardar">
            
            <div class="col-md-3"><input type="text" name="nombre" class="form-control" placeholder="Nombre" required></div>
            <div class="col-md-2"><input type="text" name="unidadMedida" class="form-control" placeholder="Unidad"></div>
            <div class="col-md-2"><input type="text" name="tipo" class="form-control" placeholder="Tipo"></div>
            <div class="col-md-2"><input type="number" name="cantidadTotal" class="form-control" placeholder="Cantidad"></div>
            <div class="col-md-3"><input type="date" name="fechaCaducidad" class="form-control"></div>
            
            <div class="col-md-2"><input type="number" step="0.01" name="precioReferencial" class="form-control" placeholder="P. Ref"></div>
            <div class="col-md-2"><input type="number" step="0.01" name="precioVenta" class="form-control" placeholder="P. Venta"></div>
            <div class="col-md-2"><input type="number" name="umbralMinimo" class="form-control" placeholder="Umbral"></div>
            <div class="col-md-2"><input type="number" name="idCategoria" class="form-control" placeholder="ID Cat" required></div>
            
            <div class="col-md-2"><input type="number" name="idEstado" class="form-control" value="1" placeholder="ID Estado" required></div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table id="tablaProductos" class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <?php foreach ($columnas as $col): ?>
                        <th><?= $col ?></th>
                    <?php endforeach; ?>
                    <th>Acciones</th> </tr>
            </thead>

            <tbody>
            <?php foreach ($productos as $p): ?>
                <?php 
                    // Lógica visual: Si idEstado es 1, es verde (Activo), si no, rojo (Inactivo)
                    $esActivo = ($p['idEstado'] == 1);
                    $claseFila = $esActivo ? '' : 'table-secondary'; // Gris si está inactivo
                ?>
                <tr class="<?= $claseFila ?>">
                    
                    <td>
                        <a href="editarProductoVista.php?id=<?= $p['idProducto'] ?>" class="text-decoration-none fw-bold">
                            <?= $p['idProducto'] ?> ✏️
                        </a>
                    </td>

                    <?php foreach (array_slice($columnas, 1) as $col): ?>
                        <td><?= $p[$col] ?></td>
                    <?php endforeach; ?>

                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="cambiar_estado">
                            <input type="hidden" name="idProducto" value="<?= $p['idProducto'] ?>">
                            
                            <?php if ($esActivo): ?>
                                <input type="hidden" name="nuevo_estado" value="0">
                                <button type="submit" class="btn btn-sm btn-danger">Desactivar</button>
                            <?php else: ?>
                                <input type="hidden" name="nuevo_estado" value="1">
                                <button type="submit" class="btn btn-sm btn-success">Activar</button>
                            <?php endif; ?>
                        </form>
                    </td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Tu script de búsqueda original
let input = document.getElementById("buscar");
let tabla = document.getElementById("tablaProductos");

input.onkeyup = function() {
    let texto = input.value.toLowerCase();
    let filas = tabla.getElementsByTagName("tr");
    // Empezamos en 1 para saltar el encabezado
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

</body>
</html>

