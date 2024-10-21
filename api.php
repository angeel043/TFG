<?php
// Configuración de cabeceras para permitir peticiones desde cualquier origen (CORS)
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Conexión a la base de datos
include_once 'config/database.php'; // Aquí es donde tendrías la configuración de la DB

// Verificar el método de la solicitud HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Verificar el endpoint solicitado
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Determinar qué recurso (endpoint) se ha solicitado
$resource = $uri[2] ?? null; // Ejemplo: /api/user, la parte "user" es el recurso

// Implementar la lógica de acuerdo al recurso solicitado
switch ($resource) {
    case 'user':
        include_once 'controllers/userController.php'; // Aquí gestionas las operaciones del usuario
        break;
    // Otros recursos como productos, pedidos, etc.
    default:
        http_response_code(404); // Respuesta si no se encuentra el recurso
        echo json_encode(['message' => 'Recurso no encontrado']);
        break;
}
?>
