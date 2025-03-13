<?php
require_once 'database.php';
session_start();

header('Content-Type: application/json');


$conn = Database::getConnection();

$idUsuario = $_SESSION['id_usuario'];

$stmt = $conn->prepare("SELECT DISTINCT sede, empresa FROM clientes WHERE completado = 0 AND idUser = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

$sedes = [];
while ($row = $result->fetch_assoc()) {
    $sedes[$row['sede']][] = $row['empresa']; // Agrupar empresas por ciudad
}

$stmt->close();
$conn->close();

echo json_encode($sedes);
exit();
?>
