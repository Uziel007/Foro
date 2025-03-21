<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $imagen = file_get_contents($_FILES['imagen']['tmp_name']);

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, password, imagen) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $contraseña, $imagen);

    if ($stmt->execute()) {
        echo "Usuario registrado con éxito";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
