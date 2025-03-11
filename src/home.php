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

// Leer datos en JSON si la solicitud no tiene parámetros clásicos
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 🔹 **Obtener Clientes (Filtro)**
    if (isset($_POST['fetchClients'])) {
        $filterBy = isset($_POST['filterBy']) ? $_POST['filterBy'] : "";
        $filterValue = isset($_POST['filterValue']) ? trim($_POST['filterValue']) : "";

        $validFilters = [
            "name" => "nombre",
            "mail" => "email",
            "phone" => "telefono",
            "company" => "empresa",
            "salaryLess" => "salario",
            "salaryMore" => "salario"
        ];

        $column = isset($validFilters[$filterBy]) ? $validFilters[$filterBy] : "";
        $sql = "SELECT id, nombre, email, telefono, completado, extrainfo, salario, horasSemanales, sede, empresa FROM clientes WHERE idUser = ? AND completado = 0";
        $params = [$id_usuario];
        $types = "i";

        if (!empty($column) && !empty($filterValue)) {
            if ($filterBy === "salaryLess") {
                $sql .= " AND $column < ?";
                $params[] = (int)$filterValue;
                $types .= "i";
            } elseif ($filterBy === "salaryMore") {
                $sql .= " AND $column > ?";
                $params[] = (int)$filterValue;
                $types .= "i";
            } else {
                $sql .= " AND LOWER($column) = LOWER(?)";
                $params[] = $filterValue;
                $types .= "s";
            }
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
        header('Content-Type: application/json');
        echo json_encode($clientes);
        exit();
    } 
    
    // 🔹 **Obtener Información del Usuario**
    elseif (isset($_POST['fetchUserInfo'])) {
        $stmt = $conn->prepare("SELECT id, nombre_usuario, email, rol FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();

        header('Content-Type: application/json');
        echo json_encode($usuario);
        exit();
    } 

    // 🔹 **Actualizar Clientes (Antes en `update_clients.php`)**
    elseif (isset($data['action']) && $data['action'] === 'update_clients') {
        $stmt = $conn->prepare("UPDATE clientes SET completado = ?, extrainfo = ? WHERE id = ? AND idUser = ?");

        foreach ($data['clients'] as $index => $client) {
            $id_cliente = $client['id'];
            $completado = $client['completado'];
            $extrainfo = $data['extrainfo'][$index]['extrainfo'] ?? ""; // Evitar valores nulos

            $stmt->bind_param("isii", $completado, $extrainfo, $id_cliente, $id_usuario);
            $stmt->execute();
        }

        $stmt->close();

        echo json_encode(['success' => true]);
        exit();
    }

    // 🔹 **Crear un Ticket**
    elseif (isset($data['action']) && $data['action'] === 'crear_ticket') {
        $idUsuario = $_SESSION['id_usuario'];
        $stmtUser = $conn->prepare("SELECT email FROM usuarios WHERE id = ?");
        $stmtUser->bind_param("i", $idUsuario);
        $stmtUser->execute();
        $resultUser = $stmtUser->get_result();
        $usuario = $resultUser->fetch_assoc();
        $stmtUser->close();

        $correoUsuario = $usuario['email'];
        $titulo = $data['titulo'];
        $descripcion = $data['descripcion'];
        $fecha = date("Y-m-d H:i:s");

        $stmtIT = $conn->prepare("SELECT id FROM usuarios WHERE rol = 3");
        $stmtIT->execute();
        $resultIT = $stmtIT->get_result();
        $usuariosIT = [];

        while ($row = $resultIT->fetch_assoc()) {
            $usuariosIT[$row['id']] = 0;
        }
        $stmtIT->close();

        if (empty($usuariosIT)) {
            echo json_encode(['success' => false, 'error' => 'No hay usuarios IT disponibles']);
            exit();
        }

        $stmtTickets = $conn->prepare("SELECT idIT, COUNT(*) AS cantidad FROM mensajes WHERE completado = 0 GROUP BY idIT");
        $stmtTickets->execute();
        $resultTickets = $stmtTickets->get_result();

        while ($row = $resultTickets->fetch_assoc()) {
            if (isset($usuariosIT[$row['idIT']])) {
                $usuariosIT[$row['idIT']] = $row['cantidad'];
            }
        }
        $stmtTickets->close();

        asort($usuariosIT);
        $idITSeleccionado = array_key_first($usuariosIT);

        $stmtInsert = $conn->prepare("INSERT INTO mensajes (idIT, idUsuario, correoUsuario, titulo, descripcion, fecha, completado) VALUES (?, ?, ?, ?, ?, ?, 0)");
        $stmtInsert->bind_param("iissss", $idITSeleccionado, $idUsuario, $correoUsuario, $titulo, $descripcion, $fecha);

        if ($stmtInsert->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmtInsert->error]);
        }

        $stmtInsert->close();
        exit();
    }
}

$conn->close();
?>
