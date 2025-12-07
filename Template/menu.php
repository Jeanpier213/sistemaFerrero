<?php
// Puedes usar este archivo como "menu.php" e incluirlo en tus páginas principales
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Lateral</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/menu.css">
</head>
<body>
 
  
    <div class="sidebar" >
        <div class="logo">
           <img  src="/Ferreteria/src/image/img-Santos.png" alt="Logo Ferretería" >
        </div>
    
        <nav class="nav flex-column">
            <hr>
            <a class="nav-link active" href="index.php"> <img src="/Ferreteria/src/logo/icon-menu.png" alt="Menu-Lateral">Menu</a>
            <a class="nav-link" href="../Vista/vistaProducto.php"> Productos</a>
            <a class="nav-link" href="../Vista/categoriaVista.php"> Categorías</a>
            <a class="nav-link" href="clientes.php"> Clientes</a>
            <a class="nav-link" href="ventas.php"> Ventas</a>
            <a class="nav-link" href="reportes.php"> Reportes</a>
            <a class="nav-link" href="configuracion.php"> Configuración</a> <br> 
            <a class="nav-link" href="logout.php"> Cerrar Sesión</a>
        </nav>
        <footer>
            <p>© 2025 Ferreteria Sa</p>
        </footer>
    </div>

    <div class="content">
        <head >
          
        <!-- MAIN DINÁMICO -->
        <main class="p-4">
            <?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                switch ($page) {
                    case 'productos':
                        include_once "../Secciones/Vista/vistaProducto.php";
                        break;
                    case 'categorias':
                        include_once "../Vista/categoriaVista.php";
                        break;
                    default:
                        echo "<h2>Bienvenido al Panel</h2><p>Selecciona una opción del menú lateral para comenzar.</p>";
                }
            } else {
                echo "<h2>Bienvenido al Panel</h2><p>Selecciona una opción del menú lateral para comenzar.</p>";
            }
            ?>
        </main>

        <!-- FOOTER FIJO -->
        <footer class="text-center p-3 border-top">
            <small>Ferretería Santos © 2025</small>
        </footer>
    </div>

</body>
</html>
