<?php
require_once 'database.php';

header('Content-Type: application/json');

try {
    // Obtener la conexión a la base de datos
    $conn = Database::getConnection();

    // Consulta para obtener los clientes junto con el email del usuario responsable
    $sql = "
        SELECT clientes.id, clientes.nombre, clientes.email, clientes.telefono, clientes.completado, 
               clientes.extraInfo, clientes.idUser, usuarios.email AS user_responsable
        FROM clientes
        LEFT JOIN usuarios ON clientes.idUser = usuarios.id
    ";

    $result = $conn->query($sql);

    if ($result) {
        $clients = [];
        while ($row = $result->fetch_assoc()) {
            $clients[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'email' => $row['email'],
                'phone' => $row['telefono'], // Cambiado a 'phone'
                'completed' => (bool)$row['completado'], // Cambiado a 'completed'
                'extra_info' => $row['extraInfo'] ?? 'No especificado', // Cambiado a 'extra_info'
                'idUser' => $row['idUser'], // idUser permanece igual
                'user_responsable' => $row['user_responsable'] ?? 'Sin asignar'
            ];
        }
        echo json_encode($clients);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
