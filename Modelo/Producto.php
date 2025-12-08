<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class Producto {

    private $pdo;

    public function __construct() {
        $this->pdo = (new BD())->conexion();
    }

    // LISTAR 
    public function listar() {
        $sql = $this->pdo->query("SELECT * FROM producto");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerColumnas() {
        $sql = $this->pdo->query("SHOW COLUMNS FROM producto");
        return $sql->fetchAll(PDO::FETCH_COLUMN);
    }

    public function agregar($nombre, $unidad, $tipo, $cantidad, $fecha, $precioRef, $precioVenta, $umbral, $idCategoria, $idEstado) {
        $sql = $this->pdo->prepare("
            INSERT INTO producto 
            (nombre, unidadMedida, tipo, cantidadTotal, fechaCaducidad, precioReferencial, precioVenta, umbralMinimo, idCategoria, idEstado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $sql->execute([$nombre, $unidad, $tipo, $cantidad, $fecha, $precioRef, $precioVenta, $umbral, $idCategoria, $idEstado]);
    }

    public function obtenerUno($id) {
        $sql = $this->pdo->prepare("SELECT * FROM producto WHERE idProducto = ?");
        $sql->execute([$id]);
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $nombre, $unidad, $tipo, $cantidad, $fecha, $precioRef, $precioVenta, $umbral, $idCategoria, $idEstado) {
        $sql = $this->pdo->prepare("
            UPDATE producto 
            SET nombre=?, unidadMedida=?, tipo=?, cantidadTotal=?, fechaCaducidad=?, precioReferencial=?, precioVenta=?, umbralMinimo=?, idCategoria=?, idEstado=?
            WHERE idProducto=?
        ");
        return $sql->execute([$nombre, $unidad, $tipo, $cantidad, $fecha, $precioRef, $precioVenta, $umbral, $idCategoria, $idEstado, $id]);
    }

    // FUNCION PARA CAMBIAR SOLO EL ESTADO
    public function cambiarEstado($id, $nuevoEstado) {
        $sql = $this->pdo->prepare("UPDATE producto SET idEstado = ? WHERE idProducto = ?");
        return $sql->execute([$nuevoEstado, $id]);
    }

    public function buscar($texto) {
        $sql = $this->pdo->prepare("
            SELECT * FROM producto
            WHERE nombre LIKE ? OR tipo LIKE ?
        ");
        $texto = "%$texto%";
        $sql->execute([$texto, $texto]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
