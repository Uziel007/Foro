<?php
include 'conexion.php';

$sql = "SELECT p.id, p.tema, p.contenido, u.nombre, 
        (SELECT COUNT(*) FROM reacciones WHERE publicacion_id = p.id AND tipo_reaccion = 'like') AS likes,
        (SELECT COUNT(*) FROM reacciones WHERE publicacion_id = p.id AND tipo_reaccion = 'dislike') AS dislikes,
        u.imagen
        FROM publicaciones p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);
$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convertir imagen en base64 si no está vacía
        if (!empty($row['imagen'])) {
            $row['imagen'] = "data:image/jpeg;base64," . base64_encode($row['imagen']);
        } else {
            $row['imagen'] = "default-avatar.png"; // Imagen por defecto
        }
        $posts[] = $row;
    }
}

echo json_encode($posts);
$conn->close();
?>
