<?php
use PHPUnit\Framework\TestCase;

// Requerimos el archivo original de la base de datos
require_once __DIR__ . '/../../Configuraciones/bd.php';

class BdTest extends TestCase {
    
    public function testConexionSeInstanciaCorrectamente() {
        // Instanciamos la clase bd de tu proyecto
        $baseDatos = new bd();
        
        // Verificamos que el objeto sea de la clase correcta
        assert($baseDatos instanceof bd);
    }
}