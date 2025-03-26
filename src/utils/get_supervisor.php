<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/***********************************************
* 
* Funcion que consulta a la BBDD los clientes completados
* aplicando filtros
* 
***********************************************/
function obtenerClientesCompletados($conn, $filterBy, $filterValue) {
    $validFilters = [
        "client_id" => "c.id",
        "client_name" => "c.nombre",
        "client_email" => "c.email",
        "client_phone" => "c.telefono",
        "user_id" => "u.id",
        "client_company" => "c.empresa",
        "client_salaryLess" => "c.salario",
        "client_salaryMore" => "c.salario"
    ];

    $column = isset($validFilters[$filterBy]) ? $validFilters[$filterBy] : "";

    $sql = "
        SELECT 
            c.id AS client_id, 
            c.nombre AS client_name, 
            c.email AS client_email, 
            c.telefono AS client_phone, 
            c.extrainfo AS client_info, 
            c.empresa AS client_company, 
            c.salario AS client_salary, 
            c.horasSemanales AS client_hours, 
            c.sede AS client_base,
            u.id AS user_id, 
            u.email AS user_email
        FROM clientes c
        INNER JOIN usuarios u ON c.idUser = u.id
        WHERE c.completado = 1
    ";

    $params = [];
    $types = "";

    if (!empty($column) && !empty($filterValue)) {
        if ($filterBy === "client_id" || $filterBy === "user_id") {
            $sql .= " AND $column = ?";
            $params[] = (int)$filterValue;
            $types .= "i";
        } elseif ($filterBy === "client_salaryLess" || $filterBy === "client_salaryMore") {
            if (!is_numeric($filterValue)) {
                echo json_encode(["error" => "El salario debe ser un número válido."]);
                exit;
            }

            $filterValue = (int)$filterValue;
            $sql .= $filterBy === "client_salaryLess" ? " AND $column <= ?" : " AND $column >= ?";
            $params[] = $filterValue;
            $types .= "i";
        } else {
            $sql .= " AND LOWER($column) LIKE LOWER(?)";
            $params[] = "%$filterValue%";
            $types .= "s";
        }
    }

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $clientes = [];

    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }

    $stmt->close();
    echo json_encode($clientes);
    exit;
}

/***********************************************
* 
* Funcion que consulta a la BBDD el numero de clientes
* completados y sin completar asociados a cada usuario
* 
***********************************************/
function obtenerEstadisticasUsuarios($conn) {
    $sql = "
        SELECT u.id AS id_usuario,
            SUM(CASE WHEN c.completado = 1 THEN 1 ELSE 0 END) AS clientes_completados,
            SUM(CASE WHEN c.completado = 0 THEN 1 ELSE 0 END) AS clientes_activos
        FROM usuarios u
        LEFT JOIN clientes c ON u.id = c.idUser
        WHERE u.rol = 1
        GROUP BY u.id
        ORDER BY u.id ASC
    ";

    $result = $conn->query($sql);
    $estadisticas = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $estadisticas[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $estadisticas]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $conn->close();
    exit();
}

/***********************************************
* 
* Funcion que consulta a la BBDD los tickets por mes
* 
***********************************************/
function obtenerTicketsPorMes($conn) {
    $sql = "SELECT mesFinal, COUNT(*) AS cantidad 
            FROM mensajes 
            WHERE completado = 1 AND mesFinal BETWEEN 1 AND 12
            GROUP BY mesFinal
            ORDER BY mesFinal ASC";

    $result = $conn->query($sql);
    $meses = array_fill(1, 12, 0); 

    while ($row = $result->fetch_assoc()) {
        $mes = intval($row['mesFinal']);
        $meses[$mes] = intval($row['cantidad']);
    }

    echo json_encode(["success" => true, "data" => $meses]);
    exit();
}


?>