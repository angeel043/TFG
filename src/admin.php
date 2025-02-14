<?php
session_start();

// Verifica si hay una sesión activa y que el usuario sea administrador
if (!isset($_SESSION['id_usuario']  ) || $_SESSION['rol'] !== 0) {
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
    }
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Accede a los parámetros
    $action = $data['action']; // Accede al valor de 'action'

    switch ($action) {
        case 'create_user':
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

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al crear usuario: ' . $conn->error]);
            }
            break;

            case 'edit_user':
                $users = $data['users']; // Accede al valor de 'users'
                
                if (isset($users) && is_array($users)) {            
                    foreach ($users as $user) {        
                        if (isset($user['id'], $user['nombre_usuario'], $user['email'], $user['rol'])) {
                            $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario = ?, email = ?, rol = ? WHERE id = ?");
                            $stmt->bind_param("ssii", $user['nombre_usuario'], $user['email'], $user['rol'], $user['id']);
                            
                            if (!$stmt->execute()) {
                                echo json_encode(['success' => false, 'error' => "$stmt->error"]);
                                exit;
                            }
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
                            exit;
                        }
                    }
            
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Datos de usuarios no enviados']);
                }
                break;

            case 'create_client':
                $nombre = $data['nombre'] ?? null;
                $email = $data['email'] ?? null;
                $telefono = $data['telefono'] ?? null;
                $idUser = isset($data['idUser']) ? intval($data['idUser']) : null;
                
                if (!$nombre || !$email || !$telefono || $idUser === null) {
                    echo json_encode(['success' => false, 'error' => 'Todos los campos obligatorios deben completarse']);
                    exit();
                }
                
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo json_encode(['success' => false, 'error' => 'Correo electrónico inválido']);
                    exit();
                }
            
                $stmt = $conn->prepare("INSERT INTO clientes (nombre, email, telefono, idUser) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssi", $nombre, $email, $telefono, $idUser);
            
                if ($stmt->execute()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Error al crear cliente: ' . $stmt->error]);
                }
                break;
                

            case 'edit_client':
                $clients = $data['clients']; // Accede al valor de 'clients'

                if (isset($clients) && is_array($clients)) {
                    foreach ($clients as $client) {
                        if (isset($client['id'], $client['nombre'], $client['email'], $client['telefono'], $client['completado'], $client['extraInfo'], $client['idUser'])) {
                            $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, email = ?, telefono = ?, completado = ?, extraInfo = ?, idUser = ? WHERE id = ?");
                            $stmt->bind_param(
                                "sssisii",
                                $client['nombre'],
                                $client['email'],
                                $client['telefono'],
                                $client['completado'],
                                $client['extraInfo'],
                                $client['idUser'],
                                $client['id']
                            );

                            if (!$stmt->execute()) {
                                echo json_encode(['success' => false, 'error' => $stmt->error]);
                                exit;
                            }
                        } else {
                            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
                            exit;
                        }
                    }

                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Datos de clientes no enviados']);
                }
                break;


        case 'delete_user':
            $id = intval($data['id']);
            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al borrar usuario: ' . $stmt->error]);
            }
            break;
        
        case 'delete_client':
            $id = intval($data['id']);
            $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al borrar cliente: ' . $stmt->error]);
            }
            break;


        default:
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $type = $_GET['type'] ?? '';

    if ($type === 'users') {
        $stmt = $conn->prepare("SELECT id, nombre_usuario, email, rol FROM usuarios");
        $stmt->execute();
        $result = $stmt->get_result();

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }

        $stmt->close();
        $conn->close();

        header('Content-Type: application/json');
        echo json_encode($usuarios);
        exit();       
    } elseif ($type === 'clients') {
        $stmt = $conn->prepare("SELECT id, nombre, email, telefono, completado, extraInfo, idUser FROM clientes");
        $stmt->execute();
        $result = $stmt->get_result();

        $clientes = [];
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }

        $stmt->close();
        $conn->close();

        header('Content-Type: application/json');
        echo json_encode($clientes);
        exit();
    }
}

$conn->close();
?>
