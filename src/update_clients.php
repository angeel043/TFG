<?php
session_start();

// Verifica si hay una sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.html");
    exit();
}

// Incluye el archivo de conexión a la base de datos
include '../src/database.php'; 

// Obtener la conexión
$conn = Database::getConnection();

// Manejar el checkbox de completado y los cambios en extra info
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Actualizar los clientes como completados
    if (!empty($_POST['completado'])) {
        $completeds = $_POST['completado'];
        foreach ($completeds as $id_cliente) {
            $stmt = $conn->prepare("UPDATE clientes SET completado = 1 WHERE id = ?");
            $stmt->bind_param("i", $id_cliente);
            $stmt->execute();
        }
    }

    // Actualizar los campos de extra info
    if (!empty($_POST['extrainfo'])) {
        foreach ($_POST['extrainfo'] as $id_cliente => $info) {
            $stmt = $conn->prepare("UPDATE clientes SET extrainfo = ? WHERE id = ?");
            $stmt->bind_param("si", $info, $id_cliente);
            $stmt->execute();
        }
    }

    // Redirigir de nuevo al listado de clientes
    header("Location: home.php");
}

$conn->close();
?>
