<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Ferretería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style/estiloGeneral.css">
    <style> body { display: flex; } </style>
</head>
<body>

    <div class="sidebar p-3 d-flex flex-column">
        <h4 class="text-center mb-4">🛠️ Ferretería</h4>
        <hr>
        <nav class="nav flex-column mb-auto">
            <a class="nav-link" href="menu.php?page=inicio"><i class="bi bi-house-door"></i> Inicio</a>
            <a class="nav-link" href="menu.php?page=productos"><i class="bi bi-box-seam"></i> Productos</a>
            <a class="nav-link" href="menu.php?page=empleados"><i class="bi bi-person-badge"></i> Empleados</a>
            <a class="nav-link" href="menu.php?page=categorias"><i class="bi bi-tags"></i> Categorías</a>
            <a class="nav-link" href="menu.php?page=entrada"><i class="bi bi-tags"></i> Entradas</a>
            <a class="nav-link text-danger mt-4" href="../Controlador/logout.php"><i class="bi bi-box-arrow-left"></i> Salir</a>
        </nav>
    </div>

    <div class="content">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'inicio';

        switch ($page) {
            case 'productos':
                include_once "../vista/productoVista.php";
                break;
            case 'editar_producto':
                include_once "../vista/editarProductoVista.php";
                break;
            case 'empleados':
                include_once "../vista/empleadoVista.php";
                break;
            case 'editar_empleado':
                include_once "../vista/editarEmpleado.php";
                break;
            case 'categorias':
                include_once "../vista/categoriaVista.php";
                break;
            case 'entrada':
                include_once "../vista/ingreso_productos_vista.php";
                break;
            case 'inicio':
            default:
                echo "<h1>Bienvenido al Panel</h1><p>Selecciona una opción del menú.</p>";
                break;
        }
        ?>
    </div>

</body>
</html>