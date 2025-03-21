<?php
include 'conexion.php';

session_start();
$usuario_id = $_SESSION['usuario_id']; // Obtener ID del usuario
$tema = $_POST['tema'];
$contenido = $_POST['contenido'];

$sql = "INSERT INTO publicaciones (usuario_id, tema, contenido) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $usuario_id, $tema, $contenido);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Publicación creada']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear publicación']);
}

$stmt->close();
$conn->close();
?>
