<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../utils/logger.php';  
require_once __DIR__ . '/../config/database.php';  

/***********************************************
* 
* Inicia sesion del usuario si los credenciales 
* son correctos y le redirige a la pagina correspondiente
* 
***********************************************/
function iniciarSesion($conn, $username, $password) {
    $stmt = $conn->prepare("SELECT id, password, rol FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
        exit();
    }

    $user = $result->fetch_assoc();
    $id_usuario = $user['id'];
    $hash_almacenado = $user['password'];
    $role = $user['rol'];

    if (!password_verify($password, $hash_almacenado)) {
        echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
        exit();
    }

    $_SESSION['id_usuario'] = $id_usuario;
    $_SESSION['rol'] = $role;

    // Registrar log de inicio de sesión (acción 13)
    registrarLog([13, $id_usuario]);

    $redirects = [
        0 => "admin.html",
        1 => "home.html",
        2 => "supervisor.html",
        3 => "it.html"
    ];

    echo json_encode([
        "success" => true,
        "message" => "Inicio de sesión exitoso",
        "redirect" => $redirects[$role] ?? "index.html",
        "rol" => $role
    ]);
    exit();
}
?>
