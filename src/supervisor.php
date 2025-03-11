<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Acceso denegado']);
    exit();
}

include 'database.php';

$conn = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Diferenciar funcionalidad según el parámetro enviado
    if (isset($_POST['fetchClients'])) {
        // Funcionalidad para recolectar datos de clientes completados
        $filterBy = isset($_POST['filterBy']) ? $_POST['filterBy'] : "";
        $filterValue = isset($_POST['filterValue']) ? trim($_POST['filterValue']) : "";

        // Mapeo de filtros válidos a las columnas de la base de datos
        $validFilters = [
            "client_id" => "c.id",
            "client_name" => "c.nombre",
            "client_email" => "c.email",
            "client_phone" => "c.telefono",
            "user_id" => "u.id",
            "client_company" => "c.empresa",
            "client_salaryLess" => "c.salario",
            "client_salaryMore" => "c.salario"
        ];

        $column = isset($validFilters[$filterBy]) ? $validFilters[$filterBy] : "";

        // Query base
        $sql = "
            SELECT 
                c.id AS client_id, 
                c.nombre AS client_name, 
                c.email AS client_email, 
                c.telefono AS client_phone, 
                c.extrainfo AS client_info, 
                c.empresa AS client_company, 
                c.salario AS client_salary, 
                c.horasSemanales AS client_hours, 
                c.sede AS client_base,
                u.id AS user_id, 
                u.email AS user_email
            FROM clientes c
            INNER JOIN usuarios u ON c.idUser = u.id
            WHERE c.completado = 1
        ";

        $params = [];
        $types = "";

        // Aplicar filtro si es válido
        if (!empty($column) && !empty($filterValue)) {
            if ($filterBy === "client_id" || $filterBy === "user_id") {
                // Filtrado por ID (numérico)
                $sql .= " AND $column = ?";
                $params[] = (int)$filterValue;
                $types .= "i"; // Tipo entero
            } elseif ($filterBy === "client_salaryLess" || $filterBy === "client_salaryMore") {
                // Asegurar que el salario es un número válido
                if (!is_numeric($filterValue)) {
                    error_log("Error: filterValue para salario no es un número: " . $filterValue);
                    die(json_encode(["error" => "El salario debe ser un número válido."]));
                }

                $filterValue = (int)$filterValue; // Convertir a entero seguro

                if ($filterBy === "client_salaryLess") {
                    $sql .= " AND $column <= ?";
                } elseif ($filterBy === "client_salaryMore") {
                    $sql .= " AND $column >= ?";
                }

                $params[] = $filterValue;
                $types .= "i"; // Salario es un entero
            } else {
                // Filtrado por texto (Empresa, Nombre, Email, Teléfono)
                $sql .= " AND LOWER($column) LIKE LOWER(?)";
                $params[] = "%$filterValue%"; // Permite búsqueda parcial
                $types .= "s"; // Tipo string
            }
        }


        // Preparar la consulta
        if ($stmt = $conn->prepare($sql)) {
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $clientes = [];
            while ($row = $result->fetch_assoc()) {
                $clientes[] = $row;
            }

            $stmt->close();
            echo json_encode($clientes);
        }
        exit();
    }

    elseif (isset($_POST['fetchUserInfo'])) {
        $id_usuario = $_SESSION['id_usuario'];
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
    
    else{
        $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                error_log("🔴 ERROR: No se recibió JSON o está mal formado.");
                echo json_encode(['error' => 'Solicitud inválida - JSON mal formado']);
                exit();
            }

            error_log("🟢 REQUEST DATA: " . print_r($data, true));

        if (isset($data['action']) && $data['action'] === 'crear_ticket') {
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

            // Buscar usuarios con rol IT
            $stmtIT = $conn->prepare("SELECT id FROM usuarios WHERE rol = 3");
            $stmtIT->execute();
            $resultIT = $stmtIT->get_result();
            $usuariosIT = [];

            while ($row = $resultIT->fetch_assoc()) {
                $usuariosIT[$row['id']] = 0; // Inicializamos contador en 0. clave valor para ver que usuario tiene menos tickets
            }
            $stmtIT->close();

            if (empty($usuariosIT)) {
                echo json_encode(['success' => false, 'error' => 'No hay usuarios IT disponibles']);
                exit();
            }

            // Contar tickets no completados por usuario IT
            $stmtTickets = $conn->prepare("SELECT idIT, COUNT(*) AS cantidad FROM mensajes WHERE completado = 0 GROUP BY idIT");
            $stmtTickets->execute();
            $resultTickets = $stmtTickets->get_result();

            while ($row = $resultTickets->fetch_assoc()) {
                if (isset($usuariosIT[$row['idIT']])) {
                    $usuariosIT[$row['idIT']] = $row['cantidad'];
                }
            }
            $stmtTickets->close();

            // Seleccionar usuario IT con menos tickets activos
            asort($usuariosIT);
            $idITSeleccionado = array_key_first($usuariosIT);

            // Insertar el ticket en la base de datos
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

}

header('Content-Type: application/json');
echo json_encode(['error' => 'Solicitud inválida']);
exit();
?>