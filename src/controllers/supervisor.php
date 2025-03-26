<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

/***********************************************
* 
* Funcion que consulta a la BBDD los tickets activos 
* de cada usuario de IT
* 
***********************************************/
function obtenerTicketsActivosPorIT($conn) {
    $sql = "
        SELECT u.id AS id_it, u.nombre_usuario AS nombre, COUNT(m.id) AS activos
        FROM usuarios u
        LEFT JOIN mensajes m ON u.id = m.idIT AND m.completado = 0
        WHERE u.rol = 3
        GROUP BY u.id, u.nombre_usuario
    ";

    $result = $conn->query($sql);
    $datos = [];

    while ($row = $result->fetch_assoc()) {
        $datos[] = $row;
    }

    echo json_encode(["success" => true, "data" => $datos]);
    exit();
}

/***********************************************
* 
* Funcion que consulta a la BBDD los tickets completados
* de cada usuario de IT
* 
***********************************************/
function obtenerTicketsCompletadosPorIT($conn) {
    $sql = "
        SELECT u.id AS id_it, u.nombre_usuario AS nombre, COUNT(m.id) AS completados
        FROM usuarios u
        LEFT JOIN mensajes m ON u.id = m.idIT AND m.completado = 1
        WHERE u.rol = 3
        GROUP BY u.id, u.nombre_usuario
    ";

    $result = $conn->query($sql);
    $datos = [];

    while ($row = $result->fetch_assoc()) {
        $datos[] = $row;
    }

    echo json_encode(["success" => true, "data" => $datos]);
    exit();
}
?>
