<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../public/index.html");
    exit();
}

include 'database.php';
$conn = Database::getConnection();
$id_usuario = $_SESSION['id_usuario'];

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Registrar todos los datos recibidos en el servidor

    if (!empty($_POST['completado'])) {
        foreach ($_POST['completado'] as $id_cliente) {
            $stmt = $conn->prepare("UPDATE clientes SET completado = 1 WHERE id = ? AND idUser = ?");
            $stmt->bind_param("ii", $id_cliente, $id_usuario);
            $stmt->execute();
        }
    }

    if (!empty($_POST['extrainfo'])) {
        foreach ($_POST['extrainfo'] as $id_cliente => $info) {
            $stmt = $conn->prepare("UPDATE clientes SET extrainfo = ? WHERE id = ? AND idUser = ?");
            $stmt->bind_param("sii", $info, $id_cliente, $id_usuario);
            $stmt->execute();
        }
    }
}

$conn->close();
exit();
?>
