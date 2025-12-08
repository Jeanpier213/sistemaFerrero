<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Ferretería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* Estilos mínimos para asegurar que el menú ocupe toda la altura */
        html, body {
            height: 100%;
            overflow-x: hidden; 
        }
        .min-vh-100 {
            min-height: 100vh !important;
        }
        .nav-link {
            color: rgba(255,255,255,0.85);
            margin-bottom: 5px;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        .bg-sidebar {
            background-color: #212529; /* Color oscuro (Dark) */
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row flex-nowrap">
        
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-sidebar min-vh-100 d-flex flex-column justify-content-between">
            
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-3 text-white min-vh-100">
                
                <a href="menu.php" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline fw-bold">🛠️ Ferretería</span>
                </a>
                
                <hr class="text-white w-100">

                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                    
                    <li class="nav-item w-100">
                        <a href="menu.php?page=inicio" class="nav-link align-middle px-0 ps-2">
                            <i class="fs-4 bi-house-door"></i> <span class="ms-1 d-none d-sm-inline">Inicio</span>
                        </a>
                    </li>

                    <li class="nav-item w-100">
                        <a href="menu.php?page=productos" class="nav-link align-middle px-0 ps-2">
                            <i class="fs-4 bi-box-seam"></i> <span class="ms-1 d-none d-sm-inline">Productos</span>
                        </a>
                    </li>

                    <li class="nav-item w-100">
                        <a href="menu.php?page=categorias" class="nav-link align-middle px-0 ps-2">
                            <i class="fs-4 bi-tags"></i> <span class="ms-1 d-none d-sm-inline">Categorías</span>
                        </a>
                    </li>

                    <li class="nav-item w-100">
                        <a href="menu.php?page=clientes" class="nav-link align-middle px-0 ps-2">
                            <i class="fs-4 bi-people"></i> <span class="ms-1 d-none d-sm-inline">Clientes</span>
                        </a>
                    </li>

                    <li class="nav-item w-100">
                        <a href="menu.php?page=ventas" class="nav-link align-middle px-0 ps-2">
                            <i class="fs-4 bi-cart"></i> <span class="ms-1 d-none d-sm-inline">Ventas</span>
                        </a>
                    </li>

                    <li class="nav-item w-100">
                        <a href="menu.php?page=reportes" class="nav-link align-middle px-0 ps-2">
                            <i class="fs-4 bi-bar-chart"></i> <span class="ms-1 d-none d-sm-inline">Reportes</span>
                        </a>
                    </li>
                    
                    <li class="nav-item w-100">
                        <a href="menu.php?page=configuracion" class="nav-link align-middle px-0 ps-2">
                            <i class="fs-4 bi-gear"></i> <span class="ms-1 d-none d-sm-inline">Configuración</span>
                        </a>
                    </li>
                </ul>
                
                <hr class="text-white w-100">
                
                <div class="pb-4 w-100">
                    <a href="../Controlador/logout.php" class="nav-link text-danger px-0 ps-2">
                        <i class="fs-4 bi-box-arrow-left"></i> <span class="ms-1 d-none d-sm-inline">Cerrar Sesión</span>
                    </a>
                </div>

            </div>
        </div>

        <div class="col py-3 bg-light">
            
            <?php
            // Lógica para cargar las vistas dinámicamente
            $page = isset($_GET['page']) ? $_GET['page'] : 'inicio';

            switch ($page) {
                case 'productos':
                    // Verifica la ruta: ../vista/productoVista.php
                    $ruta = __DIR__ . "/../vista/productoVista.php";
                    if (file_exists($ruta)) {
                        include_once $ruta;
                    } else {
                        // Mensaje de error bonito si no encuentra el archivo
                        echo '<div class="alert alert-danger shadow-sm m-4" role="alert">
                                <h4 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Archivo no encontrado</h4>
                                <p>El sistema intentó cargar <strong>../vista/productoVista.php</strong> pero no existe.</p>
                                <hr>
                                <p class="mb-0">Verifica que el nombre del archivo en la carpeta "vista" sea exactamente igual.</p>
                              </div>';
                    }
                    break;

                case 'categorias':
                    $ruta = __DIR__ . "/../vista/categoriaVista.php";
                    if (file_exists($ruta)) include_once $ruta;
                    else echo '<div class="alert alert-warning m-4">La vista de Categorías aún no ha sido creada.</div>';
                    break;
                
                case 'inicio':
                default:
                    // PANTALLA DE BIENVENIDA
                    echo '<div class="p-5 mb-4 bg-white rounded-3 shadow-sm m-3">
                            <div class="container-fluid py-5">
                              <h1 class="display-5 fw-bold text-primary">Bienvenido al Panel</h1>
                              <p class="col-md-8 fs-4">Selecciona una opción del menú lateral para comenzar a gestionar tu ferretería.</p>
                              <button class="btn btn-primary btn-lg" type="button" onclick="location.href=\'?page=productos\'">Ir a Productos</button>
                            </div>
                          </div>';
                    break;
            }
            ?>
            
        </div> </div> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
