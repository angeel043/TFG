<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/***********************************************
* 
* Funcion que consulta a la BBDD los clientes no
* completados, aplicando filtros
* 
***********************************************/
function obtenerClientes($conn, $idUser, $filterBy = "", $filterValue = "") {
    $validFilters = [
        "name" => "nombre",
        "mail" => "email",
        "phone" => "telefono",
        "company" => "empresa",
        "salaryLess" => "salario",
        "salaryMore" => "salario"
    ];

    $column = $validFilters[$filterBy] ?? "";
    $sql = "SELECT id, nombre, email, telefono, completado, extrainfo, salario, horasSemanales, sede, empresa FROM clientes WHERE idUser = ? AND completado = 0";
    $params = [$idUser];
    $types = "i";

    if (!empty($column) && !empty($filterValue)) {
        if ($filterBy === "salaryLess") {
            $sql .= " AND $column < ?";
            $params[] = (int)$filterValue;
            $types .= "i";
        } elseif ($filterBy === "salaryMore") {
            $sql .= " AND $column > ?";
            $params[] = (int)$filterValue;
            $types .= "i";
        } else {
            $sql .= " AND LOWER($column) = LOWER(?)";
            $params[] = $filterValue;
            $types .= "s";
        }
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }

    echo json_encode($clientes);
    exit();
}

/***********************************************
* 
* Funcion que consulta a la BBDD todas las sedes 
* diferentes de los clientes asociados a un usuario,
* además de las empresas asociadas a dichas sedes
* 
***********************************************/
function obtenerSedes($conn, $idUsuario) {
    $stmt = $conn->prepare("SELECT DISTINCT sede, empresa FROM clientes WHERE completado = 0 AND idUser = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    $sedes = [];
    while ($row = $result->fetch_assoc()) {
        $sedes[$row['sede']][] = $row['empresa'];
    }

    $stmt->close();
    echo json_encode($sedes);
}

?>