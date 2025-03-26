<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../utils/logger.php';  

header('Content-Type: application/json');

/***********************************************
* 
* Funcion que actualiza el estado de los tickets y
* establece el mes en el que se ha completado
* 
***********************************************/
function actualizarTicketsCompletados($conn, $ticketIds, $idUser) {
    if (empty($ticketIds) || !is_array($ticketIds)) {
        echo json_encode(["success" => false, "error" => "Datos inválidos"]);
        exit();
    }

    $mesActual = date("n");  // Mes actual (1-12)
    $placeholders = implode(",", array_fill(0, count($ticketIds), "?"));

    $sql = "UPDATE mensajes SET completado = 1, mesFinal = ? WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $types = str_repeat("i", count($ticketIds) + 1); // +1 para mesFinal
        $params = array_merge([$mesActual], $ticketIds);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();

        foreach ($ticketIds as $ticketId) {
            registrarLog([17, $idUser, $ticketId]);  // Acción 17 → completado ticket
        }

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
    exit();
}

?>
