<?php
include 'conexion.php';
session_start();

$usuario_id = $_SESSION['usuario_id'];
$publicacion_id = $_POST['publicacion_id'];
$tipo_reaccion = $_POST['tipo'];

$sql = "SELECT id FROM reacciones WHERE usuario_id = ? AND publicacion_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $usuario_id, $publicacion_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    // Insertar nueva reacción si no existe
    $insert_sql = "INSERT INTO reacciones (usuario_id, publicacion_id, tipo_reaccion) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iis", $usuario_id, $publicacion_id, $tipo_reaccion);
    $insert_stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Reacción registrada']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ya reaccionaste a esta publicación']);
}

$stmt->close();
$conn->close();
?>
