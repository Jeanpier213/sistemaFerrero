<?php 
    require_once("../Modelo/Producto.php");

    class ProductoControlador {
        private $modelo;
        public function __construct()
        {
            $this->modelo = new Producto();
        }
        public function listar() {
            return $this->modelo->listar();
        }
        public function obtenerColumnas() {
            return $this->modelo->obtenerColumnas();
        }
    }

    
?>