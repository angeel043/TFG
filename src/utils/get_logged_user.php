<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/***********************************************
* 
* Consulta a la BBDD la informacion del usuario
* iniciado sesion
* 
***********************************************/
function obtenerInfoUsuario($conn) {
    $id_usuario = $_SESSION['id_usuario'];
    $stmt = $conn->prepare("SELECT id, nombre_usuario, email, rol FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());
}
?>
