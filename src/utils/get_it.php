<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/***********************************************
* 
* Funcion que consulta a la BBBD los tickets activos
* de un usuario de IT
* 
***********************************************/
function obtenerTicketsIT($conn, $idIT) {
    $idIT = $_SESSION['id_usuario'];

    $stmt = $conn->prepare("
        SELECT id, idUsuario, correoUsuario, titulo, descripcion, fecha, completado, prioridad, sla 
        FROM mensajes 
        WHERE idIT = ? AND completado = 0
    ");
    $stmt->bind_param("i", $idIT);
    $stmt->execute();
    $result = $stmt->get_result();

    $tickets = [];
    $fechaActual = new DateTime();

    while ($row = $result->fetch_assoc()) {
        $slaFecha = new DateTime($row['sla']);
        $intervalo = $fechaActual->diff($slaFecha);
        $diasRestantes = (int)$intervalo->format('%r%a'); // Puede ser negativo si esta caducado

        if ($diasRestantes < 0) {
            $row['prioridad'] = 'caducado';
            $row['caducidad'] = '-';
        } else {
            $row['caducidad'] = $diasRestantes;
        }

        $tickets[] = $row;
    }
    $stmt->close();

    // Ordenar: caducados primero, luego prioridad, luego días restantes
    usort($tickets, function($a, $b) {
        // Caducados al principio
        if ($a['prioridad'] === 'caducado' && $b['prioridad'] !== 'caducado') return -1;
        if ($a['prioridad'] !== 'caducado' && $b['prioridad'] === 'caducado') return 1;

        // Si ambos no son caducados, ordena por prioridad (0 alta, 1 media, 2 baja)
        if ($a['prioridad'] !== 'caducado' && $b['prioridad'] !== 'caducado') {
            if ((int)$a['prioridad'] !== (int)$b['prioridad']) {
                return (int)$a['prioridad'] - (int)$b['prioridad'];
            }
            // Si misma prioridad, ordena por días restantes (menor primero)
            return $a['caducidad'] - $b['caducidad'];
        }

        return 0; // Ambos caducados
    });

    echo json_encode($tickets);
    exit();
}

/***********************************************
* 
* Funcion que consulta a la BBDD los tickets creados
* por cada rol
* 
***********************************************/
function obtenerTicketsPorRol($conn) {
    $stmt = $conn->prepare("
        SELECT u.rol, COUNT(*) AS cantidad
        FROM mensajes m
        INNER JOIN usuarios u ON m.idUsuario = u.id
        WHERE YEAR(m.fecha) = YEAR(CURDATE())
        GROUP BY u.rol
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $roles = [1 => "Usuario", 0 => "Admin", 2 => "Supervisor"];
    $ticketsPorRol = [];

    while ($row = $result->fetch_assoc()) {
        $rol = $roles[$row['rol']] ?? "Otro";
        $ticketsPorRol[$rol] = intval($row['cantidad']);
    }

    $stmt->close();
    echo json_encode(['success' => true, 'data' => $ticketsPorRol]);
    exit();
}

/***********************************************
* 
* Funcion que consulta a la BBDD todos los tickets
* completados
* 
***********************************************/
function obtenerHistorialTickets($conn, $idIT) {
    $stmt = $conn->prepare("
        SELECT id, titulo, descripcion, correoUsuario, fecha, prioridad
        FROM mensajes 
        WHERE idIT = ? AND completado = 1
        ORDER BY fecha DESC
    ");
    $stmt->bind_param("i", $idIT);
    $stmt->execute();
    $result = $stmt->get_result();

    $tickets = [];

    while ($row = $result->fetch_assoc()) {
        $fechaCreacion = new DateTime($row['fecha']);
        $fechaCaducidad = clone $fechaCreacion;
        $fechaCaducidad->modify('+30 days');
        $caducidadFormateada = $fechaCaducidad->format('Y-m-d'); 

        $tickets[] = [
            'id' => $row['id'],
            'titulo' => $row['titulo'],
            'descripcion' => $row['descripcion'],
            'correoUsuario' => $row['correoUsuario'],
            'fecha' => $fechaCreacion->format('Y-m-d H:i:s'),
            'prioridad' => $row['prioridad'],
            'caducidad' => $caducidadFormateada
        ];
    }

    $stmt->close();
    echo json_encode($tickets);
    exit();
}


?>