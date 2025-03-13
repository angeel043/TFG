<?php
session_start();
require_once '../database.php';

// Verifica si el usuario está autenticado y es admin
function verificarAutenticacion() {
    if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 0) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
        exit();
    }
}

// Conexión a la base de datos
$conn = Database::getConnection();

// Obtener información del usuario autenticado
function obtenerInfoUsuario($conn) {
    $id_usuario = $_SESSION['id_usuario'];
    $stmt = $conn->prepare("SELECT id, nombre_usuario, email, rol FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
}

// Crear un nuevo usuario
function crearUsuario($conn, $data) {
    if (empty($data['username']) || empty($data['email']) || empty($data['password']) || !isset($data['role'])) {
        echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios']);
        exit();
    }

    $username = trim($data['username']);
    $email = trim($data['email']);
    $password = password_hash(trim($data['password']), PASSWORD_BCRYPT);
    $role = intval($data['role']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Correo electrónico inválido']);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $email, $password, $role);

    echo json_encode(['success' => $stmt->execute()]);
}

// Editar usuario
function editarUsuario($conn, $data) {
    if (!isset($data['users']) || !is_array($data['users'])) {
        echo json_encode(['success' => false, 'error' => 'Datos de usuarios no enviados']);
        exit();
    }

    foreach ($data['users'] as $user) {
        if (isset($user['id'], $user['nombre_usuario'], $user['email'], $user['rol'])) {
            $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario = ?, email = ?, rol = ? WHERE id = ?");
            $stmt->bind_param("ssii", $user['nombre_usuario'], $user['email'], $user['rol'], $user['id']);
            if (!$stmt->execute()) {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            exit();
        }
    }
    echo json_encode(['success' => true]);
}

// Crear cliente
function crearCliente($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, email, telefono, idUser, empresa, sede, salario, horasSemanales) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssissii", 
        $data['nombre'], $data['email'], $data['telefono'], $data['idUser'], 
        $data['empresa'], $data['sede'], $data['salario'], $data['horas']);

    echo json_encode(['success' => $stmt->execute()]);
}

// Eliminar usuario
function eliminarUsuario($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    echo json_encode(['success' => $stmt->execute()]);
}

// Eliminar cliente
function eliminarCliente($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    echo json_encode(['success' => $stmt->execute()]);
}

// Obtener todos los usuarios
function obtenerUsuarios($conn) {
    $stmt = $conn->prepare("SELECT id, nombre_usuario, email, rol FROM usuarios");
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

// Obtener todos los clientes
function obtenerClientes($conn) {
    $stmt = $conn->prepare("SELECT id, nombre, email, telefono, completado, extraInfo, empresa, sede, salario, horasSemanales, idUser FROM clientes");
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}
