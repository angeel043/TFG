<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

require_once __DIR__ . '/../utils/logger.php'; 

/***********************************************
* 
* Funcion que crea un ticket y lo almacena en la BBDD,
* asignandolo al usuario de IT con menos tickets activos
* 
***********************************************/
function crearTicket($conn, $idUsuario, $titulo, $descripcion, $prioridad) {
    $stmtUser = $conn->prepare("SELECT email FROM usuarios WHERE id = ?");
    $stmtUser->bind_param("i", $idUsuario);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $usuario = $resultUser->fetch_assoc();
    $stmtUser->close();

    $correoUsuario = $usuario['email'];
    $fecha = date("Y-m-d H:i:s");

    // Calcular SLA: fecha actual + 30 días
    $slaDate = date("Y-m-d", strtotime("+30 days"));

    $stmtIT = $conn->prepare("SELECT id FROM usuarios WHERE rol = 3");
    $stmtIT->execute();
    $resultIT = $stmtIT->get_result();
    $usuariosIT = [];

    while ($row = $resultIT->fetch_assoc()) {
        $usuariosIT[$row['id']] = 0;
    }
    $stmtIT->close();

    if (empty($usuariosIT)) {
        echo json_encode(['success' => false, 'error' => 'No hay usuarios IT disponibles']);
        exit();
    }

    // Contar tickets no completados por IT
    $stmtTickets = $conn->prepare("SELECT idIT, COUNT(*) AS cantidad FROM mensajes WHERE completado = 0 GROUP BY idIT");
    $stmtTickets->execute();
    $resultTickets = $stmtTickets->get_result();

    while ($row = $resultTickets->fetch_assoc()) {
        if (isset($usuariosIT[$row['idIT']])) {
            $usuariosIT[$row['idIT']] = $row['cantidad'];
        }
    }
    $stmtTickets->close();

    asort($usuariosIT);
    $idITSeleccionado = array_key_first($usuariosIT);

    $stmtInsert = $conn->prepare("INSERT INTO mensajes (idIT, idUsuario, correoUsuario, titulo, descripcion, fecha, completado, prioridad, sla) VALUES (?, ?, ?, ?, ?, ?, 0, ?, ?)");
    $stmtInsert->bind_param("iissssis", $idITSeleccionado, $idUsuario, $correoUsuario, $titulo, $descripcion, $fecha, $prioridad, $slaDate);

    if ($stmtInsert->execute()) {
        $idTicket = $conn->insert_id;

        // Acción 6 = Ticket creado
        registrarLog([6, $idUsuario, $idTicket, $idITSeleccionado]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmtInsert->error]);
    }

    $stmtInsert->close();
    exit();
}
?>
