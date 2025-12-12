<?php
require_once __DIR__ . '/../Controlador/ingreso_productos_controlador.php';

$control = new IngresoProductoControlador();

$proveedores = $control->obtenerProveedores();
$trabajadores = $control->obtenerTrabajadores();
$listaProductos = $control->obtenerProductos();

$stmt = $control->listarIngresos(); 
$columnCount = $stmt->columnCount(); 

$columnasTablaForm = ["Producto", "Cantidad", "Precio Compra", "Acción"];

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guardar"])) {
    try {
        $prods_id = $_POST['prods_id'] ?? [];
        $prods_cant = $_POST['prods_cant'] ?? [];
        $prods_precio = $_POST['prods_precio'] ?? [];

        $exito = $control->guardarIngreso(
            $_POST['proveedor'], 
            $_POST['trabajador'], 
            $prods_id, 
            $prods_cant, 
            $prods_precio
        );

        if ($exito) {
            header("Location: menu.php?page=entrada&msg=exito");
            exit;
        } else {
            $mensaje = "Añada al menos un producto válido.";
            $tipoMensaje = "error";
        }
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
        $tipoMensaje = "error";
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'exito') {
    $mensaje = "Ingreso registrado correctamente.";
    $tipoMensaje = "exito";
}
?>

<link rel="stylesheet" href="">

<div class="main-container">
    <div class="header-bar">
        <h1>Ingreso de Productos</h1>
    </div>

    <?php if($mensaje): ?>
        <div class="alert <?php echo $tipoMensaje; ?>"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="panel-datos">
            <div class="campo">
                <label>Proveedor:</label>
                <select name="proveedor" required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach($proveedores as $p): ?>
                        <option value="<?php echo $p['idProveedor']; ?>"><?php echo $p['razonSocial']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="campo">
                <label>Trabajador:</label>
                <select name="trabajador" required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach($trabajadores as $t): ?>
                        <option value="<?php echo $t['idEmpleado']; ?>"><?php echo $t['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <table class="tabla-productos">
            <thead>
                <tr>
                    <?php foreach($columnasTablaForm as $columna): ?>
                        <th><?php echo $columna; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody id="tabla-body">
                <tr>
                    <td>
                        <select name="prods_id[]" required>
                            <option value="">-- Producto --</option>
                            <?php foreach($listaProductos as $prod): ?>
                                <option value="<?php echo $prod['idProducto']; ?>">
                                    <?php echo $prod['nombre']." (Ref: S/".$prod['precioReferencial'].")"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="number" name="prods_cant[]" value="1" min="1" required></td>
                    <td><input type="number" name="prods_precio[]" step="0.01" required></td>
                    <td><button type="button" class="btn-del" onclick="eliminarFila(this)">X</button></td>
                </tr>
            </tbody>
        </table>

        <div class="acciones">
            <button type="button" class="btn-add" onclick="agregarFila()">
                + Agregar Producto
            </button>
            
            <button type="submit" name="guardar" class="btn-save">Registrar Ingreso</button>
        </div>
    </form>

    <div class="historial-container">
        <h2>Historial de Ingresos</h2>
        
        <table class="tabla-historial">
            <thead>
                <tr>
                    <?php
                    for ($i = 0; $i < $columnCount; $i++) {
                        $meta = $stmt->getColumnMeta($i);
                        echo "<th>" . $meta['name'] . "</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    echo "<tr>";
                    for ($i = 0; $i < $columnCount; $i++) {
                        if ($i == 0) {
                            echo "<td><a href='ingreso_productos_vista.php?id=" . $row[$i] . "'>" . $row[$i] . "</a></td>";
                        } else {
                            echo "<td>" . $row[$i] . "</td>";
                        }
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const opcionesProductos = `
        <option value="">-- Producto --</option>
        <?php foreach($listaProductos as $prod): ?>
            <option value="<?php echo $prod['idProducto']; ?>">
                <?php echo addslashes($prod['nombre']." (Ref: S/".$prod['precioReferencial'].")"); ?>
            </option>
        <?php endforeach; ?>
    `;

    function agregarFila() {
        var tbody = document.getElementById("tabla-body");
        var fila = document.createElement("tr");
        
        fila.innerHTML = `
            <td><select name="prods_id[]" required>${opcionesProductos}</select></td>
            <td><input type="number" name="prods_cant[]" value="1" min="1" required></td>
            <td><input type="number" name="prods_precio[]" step="0.01" required></td>
            <td><button type="button" class="btn-del" onclick="eliminarFila(this)">X</button></td>
        `;
        
        tbody.appendChild(fila);
    }

    function eliminarFila(btn) {
        var fila = btn.parentNode.parentNode;
        var tbody = document.getElementById("tabla-body");
        
        if (tbody.rows.length > 1) {
            fila.parentNode.removeChild(fila);
        } else {
            alert("Debe mantener al menos un producto.");
        }
    }
</script>
