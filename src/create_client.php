<?php
header("Content-Type: application/json");
require_once 'database.php'; 

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input['fullName'], $input['email'], $input['phone'], $input['idUser'])) {
    $fullName = $input['fullName'];
    $email = $input['email'];
    $phone = $input['phone'];
    $idUser = $input['idUser'];

    // Obtener conexión a través de la clase Database
    $conn = Database::getConnection();

    // Preparar e insertar el client en la base de datos
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, email, telefono, idUser) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $fullName, $email, $phone, $idUser);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error al crear cliente"]); // Muestra el error específico de SQL
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Datos incompletos"]);
}
?>
