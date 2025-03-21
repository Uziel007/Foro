<?php
$host = 'localhost';
$user = 'root';  // Cambia esto por tu usuario
$pass = '';  // Cambia esto por tu contraseña
$db = 'foro_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
