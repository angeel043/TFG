<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../public/index.html");
    exit();
}

// Destruir la sesión
session_unset();
session_destroy();

// Redirigir al usuario a la página de inicio de sesión
header("Location: ../public/index.html");
exit();
?>
