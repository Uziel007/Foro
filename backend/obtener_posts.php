<?php
session_start();
include 'conexion.php';

$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

$sql = "SELECT p.id, p.usuario_id, p.tema, p.contenido, u.nombre, u.imagen,
        (SELECT COUNT(*) FROM reacciones WHERE publicacion_id = p.id AND tipo_reaccion = 'like') AS likes,
        (SELECT COUNT(*) FROM reacciones WHERE publicacion_id = p.id AND tipo_reaccion = 'dislike') AS dislikes
        FROM publicaciones p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);
$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['imagen'] = !empty($row['imagen']) ? "data:image/jpeg;base64," . base64_encode($row['imagen']) : "img/default-avatar.png";
        $posts[] = $row;
    }
}

$response = [
    "usuario_id" => $usuario_id,
    "posts" => $posts
];

echo json_encode($response);
$conn->close();

?>
