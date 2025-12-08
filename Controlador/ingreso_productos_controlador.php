<?php
session_start();

if (!isset($_SESSION["servidor"])) {
    header("Location: login_controlador.php");
    exit();
}

require_once __DIR__ . '/../Modelo/ingreso_productos_modelo.php';

$mensaje = "";
$tipoMensaje = "";
$modelo = null;

try {
    $modelo = new IngresoProductosModelo();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guardar"])) {
        $proveedor = $_POST['proveedor'];
        $trabajador = $_POST['trabajador'];
        $productos = $_POST['prods_id'];
        $cantidades = $_POST['prods_cant'];
        $precios = $_POST['prods_precio'];

        $detalles = [];
        for ($i = 0; $i < count($productos); $i++) {
            if ($cantidades[$i] > 0) {
                $detalles[] = [
                    'idProducto' => $productos[$i],
                    'cantidad' => $cantidades[$i],
                    'precio' => $precios[$i]
                ];
            }
        }

        if (!empty($detalles)) {
            $modelo->registrarIngreso($proveedor, $trabajador, $detalles);
            $mensaje = "Ingreso registrado correctamente.";
            $tipoMensaje = "exito";
        } else {
            $mensaje = "Añada al menos un producto.";
            $tipoMensaje = "error";
        }
    }

    $proveedores = $modelo->obtenerProveedores();
    $trabajadores = $modelo->obtenerTrabajadores();
    $listaProductos = $modelo->obtenerProductos();

} catch (Exception $e) {
    $mensaje = "Error: " . $e->getMessage();
    $tipoMensaje = "error";
    if(strpos($e->getMessage(), "Error al reconectar") !== false){
        header("Location: login_controlador.php");
        exit;
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login_controlador.php");
    exit();
}

require_once __DIR__ . '/../Vista/ingreso_productos_vista.php';
?>
