<?php
require_once 'database.php';

header('Content-Type: application/json');

try {
    $conn = Database::getConnection();
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data) && is_array($data)) {
        foreach ($data as $user) {
            // Asegúrate de que se reciben todos los campos necesarios
            if (isset($user['id'], $user['nombre'], $user['email'], $user['role'])) {
                $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario = ?, email = ?, rol = ? WHERE id = ?");
                $stmt->bind_param("ssii", $user['nombre'], $user['email'], $user['role'], $user['id']);
                if (!$stmt->execute()) {
                    echo json_encode(['success' => false, 'error' => $stmt->error]);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
                exit;
            }
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Formato de datos incorrecto']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
