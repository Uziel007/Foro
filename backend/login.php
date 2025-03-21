<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $contraseña = $_POST['contraseña'];

    // Verificar si el usuario existe en la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario && password_verify($contraseña, $usuario['password'])) {
        // Destruir sesión previa si existe
        session_start();
        session_unset();  // Limpia todas las variables de sesión
        session_destroy(); // Destruye la sesión

        // Luego, iniciar la sesión con el nuevo usuario
        session_start();  // Inicia una nueva sesión
        $_SESSION['usuario_id'] = $usuario['id'];  // Guarda el ID del usuario
        $_SESSION['nombre'] = $usuario['nombre'];  // Guarda el nombre del usuario
        $_SESSION['imagen'] = $usuario['imagen'];  // (si tienes el campo imagen) Guarda la imagen del usuario

        // Redirigir al usuario a la página de foro
        header("Location: ../frontend/foro.html");
        exit();
    } else {
        echo "Nombre o contraseña incorrectos";
    }
}
?>
