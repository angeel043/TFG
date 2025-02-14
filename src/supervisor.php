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
        $query = "
            SELECT 
                c.id AS client_id, 
                c.nombre AS client_name, 
                c.email AS client_email, 
                c.telefono AS client_phone, 
                c.extrainfo AS client_info, 
                u.id AS user_id, 
                u.email AS user_email
            FROM clientes c
            INNER JOIN usuarios u ON c.idUser = u.id
            WHERE c.completado = 1
        ";

        // Aplicar filtros si están presentes en la solicitud
        $filterBy = isset($_POST['filterBy']) ? $_POST['filterBy'] : null;
        $filterValue = isset($_POST['filterValue']) ? trim($_POST['filterValue']) : null;

        if (!empty($filterBy) && !empty($filterValue)) {
            $validFilters = [
                "client_id" => "c.id",
                "name" => "c.nombre",
                "mail" => "c.email",
                "phone" => "c.telefono",
                "user_id" => "u.id"
            ];

            if (array_key_exists($filterBy, $validFilters)) {
                $query .= " AND " . $validFilters[$filterBy] . " LIKE ?";
            }
        }

        $stmt = $conn->prepare($query);

        if (!empty($filterBy) && !empty($filterValue)) {
            $filterValue = "%$filterValue%";
            $stmt->bind_param("s", $filterValue);
        }

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

}

header('Content-Type: application/json');
echo json_encode(['error' => 'Solicitud inválida']);
exit();
?>