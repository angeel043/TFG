<?php
include_once 'database.php'; // Asegúrate de incluir el archivo de conexión a la base de datos
include_once 'userModel.php'; // Aquí se maneja la lógica relacionada a la DB

// Obtener el método de la solicitud HTTP
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    // Crear nuevo usuario (o iniciar sesión)
    $data = json_decode(file_get_contents("php://input")); // Obtenemos datos del cuerpo de la petición

    if (isset($data->action) && isset($data->username) && isset($data->password)) {
        if ($data->action == 'register') {
            $result = UserModel::register($data->username, $data->password);
            echo json_encode($result);
        } elseif ($data->action == 'login') {
            $result = UserModel::login($data->username, $data->password);
            echo json_encode($result);
        } else {
            echo json_encode(['message' => 'Acción no válida']);
        }
    } else {
        echo json_encode(['message' => 'Datos incompletos']);
    }
} elseif ($method == 'GET') {
    // Obtener lista de usuarios (solo admins)
    $users = UserModel::getAllUsers();
    echo json_encode($users);
} else {
    echo json_encode(['message' => 'Método no permitido']);
}
?>
