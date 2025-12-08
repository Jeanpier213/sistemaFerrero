<?php
class IngresoProductosModelo {
    private $pdo;

    public function __construct() {
        if (!isset($_SESSION['servidor']) || !isset($_SESSION['usuario'])) {
            throw new Exception("Sesión no iniciada.");
        }

        $dsn = "mysql:host=" . $_SESSION['servidor'] . ";dbname=" . $_SESSION['BD'] . ";port=3306;charset=utf8";
        $opciones = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
        
        try {
            $this->pdo = new PDO($dsn, $_SESSION['usuario'], $_SESSION['clave'], $opciones);
        } catch (PDOException $e) {
            throw new Exception("Error al reconectar: " . $e->getMessage());
        }
    }

    public function obtenerProveedores() {
        return $this->pdo->query("SELECT idProveedor, razonSocial FROM proveedor WHERE idEstado = 1")->fetchAll();
    }

    public function obtenerTrabajadores() {
        $sql = "SELECT e.idEmpleado, CONCAT(p.nombres, ' ', p.apellidos) as nombre 
                FROM empleado e JOIN persona p ON e.idPersona = p.idPersona 
                WHERE e.idEstado = 1";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function obtenerProductos() {
        return $this->pdo->query("SELECT idProducto, nombre, unidadMedida, precioReferencial FROM producto WHERE idEstado = 1")->fetchAll();
    }

    public function registrarIngreso($idProveedor, $idTrabajador, $detalles) {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO ingreso (fecha, idProveedor, idTrabajador) VALUES (NOW(), ?, ?)");
            $stmt->execute([$idProveedor, $idTrabajador]);
            $idIngreso = $this->pdo->lastInsertId();

            $stmtDetalle = $this->pdo->prepare("INSERT INTO detalleingreso (idIngreso, idProducto, cantidad, precioCompra, MontoTotal) VALUES (?, ?, ?, ?, ?)");
            $stmtUpdate = $this->pdo->prepare("UPDATE producto SET cantidadTotal = cantidadTotal + ? WHERE idProducto = ?");

            foreach ($detalles as $d) {
                $total = $d['cantidad'] * $d['precio'];
                $stmtDetalle->execute([$idIngreso, $d['idProducto'], $d['cantidad'], $d['precio'], $total]);
                $stmtUpdate->execute([$d['cantidad'], $d['idProducto']]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
?>