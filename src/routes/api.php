<?php
require_once '../config/database.php';

require_once '../utils/get_logged_user.php';
require_once '../utils/get_home.php';
require_once '../utils/get_admin.php';
require_once '../utils/get_supervisor.php';
require_once '../utils/get_it.php';
require_once '../utils/submit_ticket.php';

require_once '../controllers/index.php';
require_once '../controllers/home.php';
require_once '../controllers/admin.php';
require_once '../controllers/supervisor.php';
require_once '../controllers/it.php';

require_once '../import/importData.php';
require_once '../export/exportBBDD.php';

require_once '../auth/logout.php';

header('Content-Type: application/json');

$conn = Database::getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

$idUser = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

// Definir las rutas de la API
if ($method === 'GET') {
    switch ($request[0]) {
        case 'clientsHome':
            $idUser = $_GET['idUser'] ?? null;
            $filterBy = $_GET['filterBy'] ?? null;
            $filterValue = $_GET['filterValue'] ?? null;
        
            obtenerClientes($conn, $idUser, $filterBy, $filterValue);
            break;
        case 'usersAdmin':
            obtenerTodosUsuarios($conn);
            break;
        case 'clientsAdmin':
            obtenerTodosClientes($conn);
            break;
        case 'userinfo':
            obtenerInfoUsuario($conn);
            break;
        case 'export':      
            exportData($conn, $_GET['type'], $_GET['format'], $idUser);
            break;
        case 'sedes':
            if (isset($_GET['idUsuario'])) {
                obtenerSedes($conn, $idUser);
            } else {
                echo json_encode(['success' => false, 'error' => 'ID de usuario no proporcionado']);
            }
            break;
        case 'ticketsIT':
            obtenerTicketsIT($conn, $_GET['idIT'] ?? null);
            break;
        case 'clientsDone':
            $filterBy = $_GET['filterBy'] ?? "";
            $filterValue = $_GET['filterValue'] ?? "";

            obtenerClientesCompletados($conn, $filterBy, $filterValue);
            break;
        case 'userStats':
            obtenerEstadisticasUsuarios($conn, $idUser);
            break;
        case 'itActive':
            obtenerTicketsActivosPorIT($conn);
            break;
        case 'itCompleted':
            obtenerTicketsCompletadosPorIT($conn);
            break;
        case 'ticketsByMonth':
            obtenerTicketsPorMes($conn);
            break;         
        case 'ticketsByRole':
            obtenerTicketsPorRol($conn);
            break;   
        case 'ticketHistory':
            $idIT = intval($_GET['idIT'] ?? 0);
            obtenerHistorialTickets($conn, $idIT);
            break;  
        default:
            echo json_encode(['error' => 'Ruta no encontrada']);
            http_response_code(404);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    switch ($request[0]) {
        case 'login':
            $username = $data['username'] ?? null;
            $password = $data['password'] ?? null;
                
            iniciarSesion($conn, $username, $password);
            break;        
        case 'update_clients':
            $clients = $data['clients'] ?? null;
            $extraInfo = $data['extrainfo'] ?? null;

            actualizarClientes($conn, $idUser, $clients, $extraInfo);
            break;
        case 'create_user':
            crearUsuario($conn, $data, $idUser);
            break;
        case 'create_client': 
            crearCliente($conn, $data, $idUser);
            break;
        case 'edit_user':
            editarUsuario($conn, $data, $idUser);
            break;
        case 'edit_client':
            editarCliente($conn, $data, $idUser);
            break;
        case 'import_users':
            importUsers($conn, $idUser);
            break;
        case 'create_ticket':
            $titulo = $data['titulo'] ?? null;
            $descripcion = $data['descripcion'] ?? null;
            $prioridad = $data['prioridad'] ?? null;

            crearTicket($conn, $idUser, $titulo, $descripcion, $prioridad);
            break;
        case 'import_clients':
            importClients($conn, $idUser);
            break;
        case 'updateTickets':
            actualizarTicketsCompletados($conn, $data['tickets'], $idUser);
            break;
        case 'logout':
            cerrarSesion();
            break;      
        default:
            echo json_encode(['error' => 'Ruta no encontrada']);
            http_response_code(404);
    }
} elseif ($method === 'DELETE') {
    switch ($request[0]) {
        case 'delete_user':
            $data = json_decode(file_get_contents("php://input"), true); 
            if (!isset($data['id'])) {
                echo json_encode(['success' => false, 'error' => 'ID no recibido.']);
                exit;
            }
            $id = intval($data['id']);
            eliminarUsuario($conn, $id, $idUser);
            break;
        case 'delete_client':
            $data = json_decode(file_get_contents("php://input"), true); 
            if (!isset($data['id'])) {
                echo json_encode(['success' => false, 'error' => 'ID no recibido.']);
                exit;
            }
            $id = intval($data['id']);
            eliminarCliente($conn, $id, $idUser);
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
