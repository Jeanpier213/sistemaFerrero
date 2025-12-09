<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Ferretería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { min-height: 100vh; display: flex; overflow-x: hidden; }
        .sidebar { min-width: 250px; max-width: 250px; background: #212529; color: white; display: flex; flex-direction: column; min-height: 100vh; }
        .content { flex: 1; background: #f8f9fa; display: flex; flex-direction: column; }
        .nav-link { color: rgba(255,255,255,.8); margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { color: #fff; background: rgba(255,255,255,.1); border-radius: 5px; }
    </style>
</head>
<body>

    <div class="sidebar p-3">
        <h4 class="text-center mb-4">🛠️ Ferretería</h4>
        <hr>
        <nav class="nav flex-column mb-auto">
            <a class="nav-link" href="menu.php?page=inicio"><i class="bi bi-house-door"></i> Inicio</a>
            <a class="nav-link" href="menu.php?page=productos"><i class="bi bi-box-seam"></i> Productos</a>
            <a class="nav-link" href="menu.php?page=inventario"><i class="bi-clipboard-plus"></i> Inventario</a>
            <a class="nav-link" href="menu.php?page=categorias"><i class="bi bi-tags"></i> Categorías</a>
            <a class="nav-link" href="menu.php?page=clientes"><i class="bi bi-people"></i> Clientes</a>
            <a class="nav-link" href="menu.php?page=empleados"><i class="bi bi-person-badge"></i> Empleados</a>
            <a class="nav-link" href="menu.php?page=ventas"><i class="bi bi-cart"></i> Ventas</a>
            <a class="nav-link text-danger mt-4" href="../Controlador/logout.php"><i class="bi bi-box-arrow-left"></i> Salir</a>
        </nav>
        <small class="text-center text-muted mt-auto">© 2025 Sistema</small>
    </div>

    <div class="content">
        <main class="p-4">
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'inicio';

            switch ($page) {
                // RUTA: PRODUCTOS (LISTA)
                case 'productos':
                    $ruta = __DIR__ . "/../vista/productoVista.php";
                    if (file_exists($ruta)) include_once $ruta;
                    else echo "<div class='alert alert-danger'>No se encuentra: $ruta</div>";
                    break;

                // RUTA: Ingreso Productos
                case 'inventario':
                    $ruta = __DIR__ . "/../vista/ingreso_productos_vista.php";
                    if (file_exists($ruta)) include_once $ruta;
                    else echo "<div class='alert alert-danger'>No se encuentra: $ruta</div>";
                    break;

                // RUTA: EDITAR PRODUCTO (NUEVA)
                case 'editar':
                    $ruta = __DIR__ . "/../vista/editarProductoVista.php";
                    if (file_exists($ruta)) include_once $ruta;
                    else echo "<div class='alert alert-danger'>No se encuentra: $ruta</div>";
                    break;

                // RUTA: CATEGORIAS
                case 'categorias':
                    $ruta = __DIR__ . "/../vista/categoriaVista.php";
                    if (file_exists($ruta)) include_once $ruta;
                    break;
                // CASO EMPLEADOS
                case 'empleados':
                    $ruta = __DIR__ . "/../vista/empleadoVista.php";
                    if (file_exists($ruta)) include_once $ruta;
                    else echo "<div class='alert alert-danger'>Falta crear vista/empleadoVista.php</div>";
                    break;

                // CASO EDITAR EMPLEADO
                case 'editar_empleado':
                    $ruta = __DIR__ . "/../vista/editarEmpleado.php";
                    if (file_exists($ruta)) include_once $ruta;
                    break;

                case 'inicio':
                default:
                    echo "<div class='p-5 bg-white shadow-sm rounded'>
                            <h1>Bienvenido al Panel</h1>
                            <p>Selecciona una opción del menú.</p>
                        </div>";
                    break;
            }
            ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
