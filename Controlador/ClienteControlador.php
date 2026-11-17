<?php
require_once __DIR__ . '/../Modelo/Cliente.php';

// 1. CAPTURAR PETICIÓN RÁPIDA DESDE EL PUNTO DE VENTA (AJAX)
$op = $_GET['op'] ?? '';

if ($op === 'guardarAjax') {
    $datos = json_decode(file_get_contents('php://input'), true);
    $modeloCliente = new Cliente();
    
    try {
        $idCliente = $modeloCliente->registrarRapido($datos['tipoDoc'], $datos['numDoc'], $datos['nombres'], $datos['direccion'], $datos['telefono']);
        
        if ($idCliente) {
            echo json_encode(["status" => "success", "idCliente" => $idCliente]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => "Error al guardar en Base de Datos."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "mensaje" => $e->getMessage()]);
    }
    exit; // Detiene la ejecución aquí para que el Ajax responda limpio
}


// 2. CONTROLADOR NORMAL PARA EL MÓDULO DE GESTIÓN
class ClienteControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Cliente();
    }

    public function listarClientes() {
        return $this->modelo->listarTodos();
    }

    public function guardarCliente($tipoDoc, $numeroDoc, $nombres, $direccion, $telefono) {
        return $this->modelo->registrar($tipoDoc, $numeroDoc, $nombres, $direccion, $telefono);
    }

    // NUEVA FUNCIÓN PARA ACTUALIZAR CLIENTE (EDICIÓN)
    public function actualizarCliente($idCliente, $tipoDoc, $numeroDoc, $nombres, $direccion, $telefono) {
        // Llama a la función 'actualizar' del Modelo
        return $this->modelo->actualizar($idCliente, $tipoDoc, $numeroDoc, $nombres, $direccion, $telefono);
    }
}
?>