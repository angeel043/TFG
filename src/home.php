<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../public/index.html");
    exit();
}

include 'database.php';

$conn = Database::getConnection();
$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fetchClients'])) {
        // Obtener parámetros de filtro desde la solicitud
        $filterBy = isset($_POST['filterBy']) ? $_POST['filterBy'] : "";
        $filterValue = isset($_POST['filterValue']) ? trim($_POST['filterValue']) : "";

        // Mapeo de filtros válidos a las columnas de la base de datos
        $validFilters = [
            "name" => "nombre",
            "mail" => "email",
            "phone" => "telefono"
        ];

        $column = isset($validFilters[$filterBy]) ? $validFilters[$filterBy] : "";

        // Construir la consulta SQL
        $sql = "SELECT id, nombre, email, telefono, completado, extrainfo FROM clientes WHERE idUser = ? and completado = 0";
        $params = [$id_usuario];
        $types = "i";  // Tipo de dato para idUser (entero)

        if (!empty($column) && !empty($filterValue)) {
            $sql .= " AND LOWER($column) = LOWER(?)";
            $params[] = $filterValue;
            $types .= "s"; // Tipo de dato para el filtro (string)
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $clientes = [];
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }

        $stmt->close();

        // Responder con los datos filtrados
        header('Content-Type: application/json');
        echo json_encode($clientes);
        exit();
    } elseif (isset($_POST['fetchUserInfo'])) {
        // Obtener la información del usuario autenticado
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
    }
}

// Cerrar conexión
$conn->close();
?>
