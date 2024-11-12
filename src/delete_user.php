<?php
require_once 'database.php';

header('Content-Type: application/json');

// Obtener la conexión a la base de datos
$conn = Database::getConnection();

// Verificar si se envió la ID
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $id = $data['id'];

    // Consulta para eliminar el user
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionada.']);
}
?>
