<?php
session_start();

// Verifica si hay una sesión activa y que el usuario sea administrador
if (!isset($_SESSION['id_usuario']  ) || $_SESSION['rol'] !== 3) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit();
}

header('Content-Type: application/json'); // Establece el encabezado JSON

include '../src/database.php';

// Obtener la conexión
$conn = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificar si se solicita información del usuario
    if (isset($data['fetchUserInfo']) && $data['fetchUserInfo'] === true) {
        $id_usuario = $_SESSION['id_usuario'];

        $stmt = $conn->prepare("SELECT id, nombre_usuario, email, rol FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        $usuario = $result->fetch_assoc();

        $stmt->close();

        // Responder con los datos del usuario
        header('Content-Type: application/json');
        echo json_encode($usuario);
        exit();
    } else if (isset($_POST['fetchTickets'])) {
        $idIT = $_SESSION['id_usuario']; // Usuario IT logueado

        $stmt = $conn->prepare("
            SELECT id, idUsuario, correoUsuario, titulo, descripcion, fecha, completado 
            FROM mensajes 
            WHERE idIT = ? AND completado = 0
        ");
        $stmt->bind_param("i", $idIT);
        $stmt->execute();
        $result = $stmt->get_result();

        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
        $stmt->close();

        echo json_encode($tickets);
        exit();
    } else if (isset($data['action']) && $data['action'] === "update_tickets") {

        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE mensajes SET completado = 1 WHERE id IN (" . implode(",", array_fill(0, count($data['tickets']), "?")) . ")");
        
        if ($stmt) {
            $stmt->bind_param(str_repeat("i", count($data['tickets'])), ...$data['tickets']);
            $stmt->execute();
            $stmt->close();
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        exit();
    }
}

$conn->close();
?>