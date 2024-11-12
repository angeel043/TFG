<?php
require_once 'database.php';

header('Content-Type: application/json');

try {
    // Obtener la conexión a la base de datos
    $conn = Database::getConnection();

    // Consulta para obtener los users
    $sql = "SELECT id, nombre_usuario, email, rol FROM usuarios";
    $result = $conn->query($sql);

    if ($result) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = [
                'id' => $row['id'],
                'nombre_user' => $row['nombre_usuario'],
                'email' => $row['email'],
                'role' => isset($row['rol']) ? (int)$row['rol'] : null // Garantiza que 'role' siempre sea válido
            ];
        }
        echo json_encode($users);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
