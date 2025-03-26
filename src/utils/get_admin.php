<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/***********************************************
* 
* Funcion que consulta a la BBDD todos los usuarios
* 
***********************************************/
function obtenerTodosUsuarios($conn) {
    $stmt = $conn->prepare("SELECT id, nombre_usuario, email, rol FROM usuarios");
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

/***********************************************
* 
* Funcion que consulta a la BBDD todos los clientes
* 
***********************************************/
function obtenerTodosClientes($conn) {
    $stmt = $conn->prepare("SELECT id, nombre, email, telefono, completado, extraInfo, empresa, sede, salario, horasSemanales, idUser FROM clientes");
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}
?>