<?php
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../vista/login_vista.php");
    exit();
}
?>
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
    <div class="sidebar p-3 d-flex flex-column" style="width: 250px; min-height: 100vh; background-color: #212529; color: white;">
        <h4 class="text-center mb-4">
            <img src="../src/image/img-santos.png" alt="Logo Ferretería" style="width:90px; height:90px; vertical-align:middle;">
        </h4>
        <hr>
        <nav class="nav flex-column mb-auto">
            <a class="nav-link text-white" href="menu.php?page=inicio"><i class="bi bi-house-door"></i> Inicio</a>
            <a class="nav-link text-white" href="menu.php?page=pos"><i class="bi bi-cart-plus"></i> Punto de Venta</a>
            <a class="nav-link text-white" href="menu.php?page=productos"><i class="bi bi-box-seam"></i> Productos</a>
            <a class="nav-link text-white" href="menu.php?page=empleados"><i class="bi bi-person-badge"></i> Empleados</a>
            <a class="nav-link text-white" href="menu.php?page=clientes"><i class="bi bi-person-vcard"></i> Clientes</a>
            <a class="nav-link text-white" href="menu.php?page=categorias"><i class="bi bi-tags"></i> Categorías</a>
            <a class="nav-link text-white" href="menu.php?page=proveedor"><i class="bi bi-truck"></i> Proveedores</a>
            <a class="nav-link text-white" href="menu.php?page=usuarios"><i class="bi bi-people"></i> Usuarios</a>  
            <a class="nav-link text-white" href="menu.php?page=gestion"><i class="bi bi-gear"></i> Gestión</a> 
            <hr>
            <p><b>Inventario</b></p>
            <a class="nav-link text-white" href="menu.php?page=entrada"><i class="bi bi-clipboard-plus"></i> Entradas</a>
            <a class="nav-link text-white" href="menu.php?page=salidas"><i class="bi bi-clipboard-minus"></i> Salidas</a>
            <a class="nav-link text-danger mt-4" href="../Controlador/logout.php"><i class="bi bi-box-arrow-left"></i> Salir</a>
        </nav>
    </div>

    <div class="content flex-grow-1 p-4">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'inicio';

        switch ($page) {
            case 'inicio': include_once "../vista/inicio.php"; break;
            case 'pos': include_once "../vista/pos.php"; break;
            case 'gestion': include_once "../vista/gestion.php"; break;
            case 'productos': include_once "../vista/productoVista.php"; break;
            case 'editar_producto': include_once "../vista/editarProductoVista.php"; break;
            case 'empleados': include_once "../vista/empleadoVista.php"; break;
            case 'editar_empleado': include_once "../vista/editarEmpleado.php"; break;
            case 'clientes': include_once "../vista/clienteVista.php"; break;
            case 'usuarios': include_once "../vista/usuarioVista.php"; break;
            case 'proveedor': include_once "../vista/proveedorVista.php"; break;
            case 'editar_proveedor': include_once "../vista/editarProveedorVista.php"; break;
            case 'categorias': include_once "../vista/categoriaVista.php"; break;
            case 'editar_categorias': include_once "../vista/categoria-editarVista.php"; break;
            case 'entrada': include_once "../vista/ingreso_productos_vista.php"; break;
            case 'salidas': include_once "../vista/salidaInventarioVista.php"; break;
            case 'editar_salidas': include_once "../vista/salidainventario-editarVista.php"; break;
            default: include_once "../vista/inicio.php"; break;
        }
        ?>
    </div>
    <!-- Agrega esto justo antes de </body> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<style>
    /* Estilos mejorados para el Sidebar */
    .sidebar {
        background-color: #1e1e2d; /* Un tono oscuro más moderno y menos agresivo */
        min-height: 100vh;
        box-shadow: 4px 0 10px rgba(0,0,0,0.1);
    }
    
    .sidebar-logo-container {
        padding: 1.5rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        margin-bottom: 1rem;
    }

    /* Enlaces del menú principal */
    .sidebar .nav-link {
        color: #a2a3b7; /* Color de texto gris claro */
        padding: 0.75rem 1.5rem;
        margin: 0.2rem 1rem;
        border-radius: 8px; /* Bordes redondeados */
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px; /* Separación entre ícono y texto */
    }

    /* Efecto al pasar el ratón */
    .sidebar .nav-link:hover {
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.05);
        transform: translateX(5px); /* Pequeño salto a la derecha */
    }

    /* Estilo para la pestaña ACTIVA */
    .sidebar .nav-link.active {
        color: #ffffff;
        background-color: #0d6efd; /* Azul primary de Bootstrap */
        box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2);
    }

    /* Subtítulos de sección (ej: "Inventario") */
    .sidebar-heading {
        color: #565674;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1px;
        padding: 1rem 1.5rem 0.5rem;
    }

    /* Botón de salir */
    .sidebar .btn-logout {
        color: #f1416c;
        margin-top: auto;
    }
    
    .sidebar .btn-logout:hover {
        background-color: rgba(241, 65, 108, 0.1);
        color: #f1416c;
    }
</style>