<?php
session_start();

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['email_usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Acceso denegado. Inicia sesión.']);
    exit();
}

include 'database.php';

$conn = Database::getConnection();

// Leer el cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['mensaje'])) {
    $mensaje = trim($data['mensaje']);
    $idUsuario = $_SESSION['id_usuario'];
    $correoUsuario = $_SESSION['email_usuario'];

    if (empty($mensaje)) {
        echo json_encode(['success' => false, 'error' => 'El mensaje no puede estar vacío.']);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO mensajes (idUsuario, correoUsuario, mensaje, cerrado) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("iss", $idUsuario, $correoUsuario, $mensaje);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al guardar el mensaje: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Solicitud inválida.']);
}
?>
