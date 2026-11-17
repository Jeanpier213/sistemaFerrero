<?php
require_once __DIR__ . '/../Configuraciones/bd.php';

class Proveedor {
    private $pdo;

    public function __construct() {
        $this->pdo = (new BD())->conexion();
    }

    // LISTAR (Incluye pr.idPersona y p.dni requeridos para la vista y edición)
    public function listar() {
        $sql = $this->pdo->prepare("
            SELECT pr.idProveedor, pr.idPersona, pr.razonSocial, pr.ruc, pr.direccion, pr.tipoProductos, pr.idEstado,
                   p.nombres, p.apellidos, p.dni, p.telefono, p.email
            FROM proveedor pr
            INNER JOIN persona p ON pr.idPersona = p.idPersona
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // AGREGAR (Transacción: Primero Persona, luego Proveedor)
    public function agregar($nombres, $apellidos, $dni, $telefono, $email, $razonSocial, $ruc, $direccion, $tipoProd) {
        try {
            $this->pdo->beginTransaction();

            // 1. Insertar Contacto (Persona)
            $sqlP = $this->pdo->prepare("INSERT INTO persona (nombres, apellidos, dni, telefono, email) VALUES (?,?,?,?,?)");
            $sqlP->execute([$nombres, $apellidos, $dni, $telefono, $email]);
            $idPersona = $this->pdo->lastInsertId();

            // 2. Insertar Empresa (Proveedor)
            $sqlPr = $this->pdo->prepare("INSERT INTO proveedor (idPersona, razonSocial, ruc, direccion, tipoProductos, idEstado) VALUES (?,?,?,?,?,1)");
            $sqlPr->execute([$idPersona, $razonSocial, $ruc, $direccion, $tipoProd]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // OBTENER UNO (Para editar)
    public function obtenerUno($id) {
        $sql = $this->pdo->prepare("
            SELECT pr.idProveedor, pr.idPersona, pr.razonSocial, pr.ruc, pr.direccion, pr.tipoProductos, pr.idEstado,
                   p.nombres, p.apellidos, p.dni, p.telefono, p.email
            FROM proveedor pr
            INNER JOIN persona p ON pr.idPersona = p.idPersona
            WHERE pr.idProveedor = ?
        ");
        $sql->execute([$id]);
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    // ACTUALIZAR
    public function actualizar($idProveedor, $idPersona, $nombres, $apellidos, $dni, $telefono, $email, $razonSocial, $ruc, $direccion, $tipoProd, $idEstado) {
        try {
            $this->pdo->beginTransaction();
            // Actualizar Persona
            $sqlP = $this->pdo->prepare("UPDATE persona SET nombres=?, apellidos=?, dni=?, telefono=?, email=? WHERE idPersona=?");
            $sqlP->execute([$nombres, $apellidos, $dni, $telefono, $email, $idPersona]);
            
            // Actualizar Proveedor
            $sqlPr = $this->pdo->prepare("UPDATE proveedor SET razonSocial=?, ruc=?, direccion=?, tipoProductos=?, idEstado=? WHERE idProveedor=?");
            $sqlPr->execute([$razonSocial, $ruc, $direccion, $tipoProd, $idEstado, $idProveedor]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // CAMBIAR ESTADO
    public function cambiarEstado($id, $estado) {
        $sql = $this->pdo->prepare("UPDATE proveedor SET idEstado = ? WHERE idProveedor = ?");
        return $sql->execute([$estado, $id]);
    }

    // BUSCAR
    public function buscar($texto) {
        $texto = "%$texto%";
        $sql = $this->pdo->prepare("
            SELECT pr.idProveedor, pr.idPersona, pr.razonSocial, pr.ruc, pr.tipoProductos, pr.idEstado, 
                   p.nombres, p.apellidos, p.dni, p.telefono, p.email
            FROM proveedor pr
            INNER JOIN persona p ON pr.idPersona = p.idPersona
            WHERE pr.razonSocial LIKE ? OR pr.ruc LIKE ? OR p.nombres LIKE ? OR p.dni LIKE ?
        ");
        $sql->execute([$texto, $texto, $texto, $texto]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>