<?php
require_once 'database.php';

header('Content-Type: application/json');

try {
    $conn = Database::getConnection();
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data) && is_array($data)) {
        foreach ($data as $client) {
            if (isset($client['id'], $client['nombre'], $client['email'], $client['phone'], $client['completed'], $client['extra_info'], $client['idUser'])) {
                $stmt = $conn->prepare("UPDATE clients SET nombre = ?, email = ?, phone = ?, completed = ?, extra_info = ?, idUser = ? WHERE id = ?");
                $stmt->bind_param("sssisis", $client['nombre'], $client['email'], $client['phone'], $client['completed'], $client['extra_info'], $client['idUser'], $client['id']);

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
