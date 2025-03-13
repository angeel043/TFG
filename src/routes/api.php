<?php
include '../database.php';
require_once '../admin.php';
require_once '../home.php';
require_once '../supervisor.php';
require_once '../it.php';

header('Content-Type: application/json');

$conn = Database::getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

// Definir las rutas de la API
if ($method === 'GET') {
    switch ($request[0]) {
        case 'users':
            obtenerUsuarios($conn);
            break;
        case 'clients':
            obtenerClientes($conn);
            break;
        case 'userinfo':
            obtenerInfoUsuario($conn);
            break;
        default:
            echo json_encode(['error' => 'Ruta no encontrada']);
            http_response_code(404);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    switch ($request[0]) {
        case 'create_user':
            crearUsuario($conn, $data);
            break;
        case 'edit_user':
            editarUsuario($conn, $data);
            break;
        case 'create_client':
            crearCliente($conn, $data);
            break;
        default:
            echo json_encode(['error' => 'Ruta no encontrada']);
            http_response_code(404);
    }
} elseif ($method === 'DELETE') {
    switch ($request[0]) {
        case 'delete_user':
            eliminarUsuario($conn, $request[1]); // ID del usuario en la URL
            break;
        case 'delete_client':
            eliminarCliente($conn, $request[1]); // ID del cliente en la URL
            break;
        default:
            echo json_encode(['error' => 'Ruta no encontrada']);
            http_response_code(404);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
    http_response_code(405);
}

$conn->close();
?>
