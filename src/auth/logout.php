<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../utils/logger.php'; 

/***********************************************
* 
* Funcion para cerrar la sesion actual
* 
***********************************************/
function cerrarSesion() {
    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(['success' => false, 'message' => 'No hay sesión activa.']);
        exit();
    }

    $idUsuario = $_SESSION['id_usuario']; 

    registrarLog([14, $idUsuario]); // Acción 14 = cerrar sesión

    session_unset();
    session_destroy();

    echo json_encode(['success' => true, 'message' => 'Sesión cerrada correctamente.']);
    exit();
}
?>
