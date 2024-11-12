<?php
header("Content-Type: application/json");
require_once 'database.php'; 

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input['username'], $input['email'], $input['password'], $input['role'])) {
    $username = $input['username'];
    $email = $input['email'];
    $password = password_hash($input['password'], PASSWORD_BCRYPT);
    $role = $input['role'];

    // Obtener conexión a través de la clase Database
    $conn = Database::getConnection();

    // Preparar e insertar el usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error al crear usuario"]);
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
}

?>

