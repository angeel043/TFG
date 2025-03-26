<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../utils/logger.php'; 

/***********************************************
* 
* Actualiza los campos "completado" e "informacion
* extra" de los clientes en la BBDD
* 
***********************************************/
function actualizarClientes($conn, $id_usuario, $clients, $extraInfo) {
    if (!is_array($clients)) {
        echo json_encode(['success' => false, 'error' => 'Clientes inválidos']);
        exit();
    }

    $stmt = $conn->prepare("UPDATE clientes SET completado = ?, extrainfo = ? WHERE id = ? AND idUser = ?");

    foreach ($clients as $index => $client) {
        $id_cliente = $client['id'];
        $completado = $client['completado'];
        $extrainfo = $extraInfo[$index]['extrainfo'] ?? "";

        $stmt->bind_param("isii", $completado, $extrainfo, $id_cliente, $id_usuario);
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
            $stmt->close();
            exit();
        }

        // Solo registrar si ha cambiado algo Y si se marcó como completado (1)
        if ($stmt->affected_rows > 0 && $completado == 1) {
            registrarLog([16, $id_usuario, $id_cliente]); // Acción 16: cliente marcado completado
        }
    }

    $stmt->close();
    echo json_encode(['success' => true]);
    exit();
}
?>
