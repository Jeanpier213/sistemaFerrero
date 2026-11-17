<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class Dashboard {
    private $pdo;

    public function __construct() {
        $this->pdo = (new BD())->conexion();
    }

    // 1. Obtener el total de ventas (dinero) del día actual
    public function obtenerVentasDelDia() {
        $sql =$this->pdo->query("SELECT SUM(total) as total_dia FROM venta WHERE DATE(fecha) = CURDATE()");
        $resultado =$sql->fetch(PDO::FETCH_ASSOC);
        return $resultado['total_dia'] ?$resultado['total_dia'] : 0.00;
    }

    // 2. Obtener productos que están por debajo de su umbral mínimo (Para comprar)
    public function obtenerAlertasStock() {
        $sql =$this->pdo->query("SELECT nombre, cantidadTotal, umbralMinimo FROM producto WHERE cantidadTotal <= umbralMinimo AND idEstado = 1");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Cantidad de ventas realizadas hoy (Tickets emitidos)
    public function obtenerCantidadVentasHoy() {
        $sql =$this->pdo->query("SELECT COUNT(idVenta) as cantidad FROM venta WHERE DATE(fecha) = CURDATE()");
        $resultado =$sql->fetch(PDO::FETCH_ASSOC);
        return $resultado['cantidad'];
    }

    // 4. NUEVO: Listar productos para el panel central (ej. los primeros 10 o todos)
    public function obtenerTodosLosProductos() {
        $sql =$this->pdo->query("
            SELECT nombre, cantidadTotal, umbralMinimo 
            FROM producto 
            WHERE idEstado = 1 
            ORDER BY cantidadTotal ASC 
            LIMIT 10
        ");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>