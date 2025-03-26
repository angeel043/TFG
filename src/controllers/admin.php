<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/../utils/logger.php'; 

/***********************************************
* 
* Funcion para crear un nuevo usuario y almacenarlo
* en la BBDD
* 
***********************************************/
function crearUsuario($conn, $data, $idUser) {
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
        $newUserId = $conn->insert_id;
        registrarLog([0, $idUser, $newUserId]);  // Accion 0 - Crear usuario
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    exit();
}

/***********************************************
* 
* Funcion que edita los datos de los usuarios y los
* actualiza en la BBDD
* 
***********************************************/ 
function editarUsuario($conn, $data, $idUser) {
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
            if ($stmt->affected_rows > 0) {
                registrarLog([2, $idUser, $user['id']]);  // Accion 2 - Modificar usuario
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            exit();
        }
    }
    echo json_encode(['success' => true]);
}

/***********************************************
* 
* Funcion para crear un nuevo cliente y almacenarlo
* en la BBDD
* 
***********************************************/
function crearCliente($conn, $data, $idUser) {
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, email, telefono, idUser, empresa, sede, salario, horasSemanales) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssissii", 
        $data['nombre'], $data['email'], $data['telefono'], $data['idUser'], 
        $data['empresa'], $data['sede'], $data['salario'], $data['horas']);

    if ($stmt->execute()) {
        $newClientId = $conn->insert_id;
        registrarLog([1, $idUser, $newClientId]);  // Accion 1 - Crear cliente
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
}

/***********************************************
* 
* Funcion que edita los datos de los clientes y los
* actualiza en la BBDD
* 
***********************************************/
function editarCliente($conn, $data, $idUser) {
    if (!isset($data['clients']) || !is_array($data['clients'])) {
        echo json_encode(['success' => false, 'error' => 'Datos de clientes no enviados']);
        exit();
    }

    foreach ($data['clients'] as $client) {
        $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, email = ?, telefono = ?, empresa = ?, sede = ?, salario = ?, horasSemanales = ?, completado = ?, extraInfo = ?, idUser = ? WHERE id = ?");
        $stmt->bind_param("ssissiiisii", $client['nombre'], $client['email'], $client['telefono'], $client['empresa'], $client['sede'], $client['salario'], $client['horas'], $client['completado'], $client['extraInfo'], $client['idUser'], $client['id']);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
            exit();
        }
        if ($stmt->affected_rows > 0) {
            registrarLog([3, $idUser, $client['id']]);  // Accion 3 - Modificar cliente
        }
    }
    echo json_encode(['success' => true]);
}

/***********************************************
* 
* Funcion que elimina un usuario de la BBDD
* 
***********************************************/
function eliminarUsuario($conn, $id, $idUser) {
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'ID inválido']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            registrarLog([4, $idUser, $id]);  // Accion 4 - Eliminar usuario
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se encontró el usuario']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Error en la consulta: ' . $stmt->error]);
    }
}

/***********************************************
* 
* Funcion que elimina un cliente de la BBDD
* 
***********************************************/
function eliminarCliente($conn, $id, $idUser) {
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'ID inválido']);
        return;
    }

    $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            registrarLog([5, $idUser, $id]);  // Accion 5 - Eliminar cliente
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se encontró el cliente']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Error en la consulta: ' . $stmt->error]);
    }
}
?>