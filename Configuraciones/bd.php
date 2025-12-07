<?php
$host = "localhost";
$dbname = "ferreteriasantos_db";
$user = "root";
$pass = ""; // tu contraseña si tiene

class bd {
    public function conexion() {
        global $host, $dbname, $user, $pass;
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
     return $pdo;

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
}
}


?>
