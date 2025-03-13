<?php
require '../vendor/autoload.php'; // Cargar PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit();
}

header('Content-Type: application/json');
include '../src/database.php';
$conn = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['file']) || !isset($_POST['action'])) {
        echo json_encode(['success' => false, 'message' => 'Falta archivo o acción.']);
        exit();
    }

    $action = $_POST['action'];
    $allowedExtensions = ["xlsx", "csv"];
    $fileExtension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['success' => false, 'message' => 'Formato no permitido. Usa .xlsx o .csv']);
        exit();
    }

    $tmpFilePath = $_FILES["file"]["tmp_name"];
    $spreadsheet = IOFactory::load($tmpFilePath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    $inserted = 0;

    if ($action === 'import_users') {
        foreach ($data as $index => $row) {
            if (empty($row[0])) break; // Si la primera celda está vacía, detenerse

            [$nombre_usuario, $email, $password, $rol] = $row;
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $nombre_usuario, $email, $hashedPassword, $rol);
            $stmt->execute();
            $inserted++;
        }
        echo json_encode(["success" => true, "message" => "$inserted usuarios importados con éxito."]);
    } elseif ($action === 'import_clients') {
        foreach ($data as $index => $row) {
            if (empty(trim($row[0]))) break;
    
            [$nombre, $email, $telefono, $idUser, $salario, $horasSemanales, $sede, $empresa] = $row;
            
            $idUser = !empty($idUser) ? intval($idUser) : NULL;
            $salario = !empty($salario) ? intval($salario) : 0;
            $horasSemanales = !empty($horasSemanales) ? intval($horasSemanales) : 0;
    
            $stmt = $conn->prepare("INSERT INTO clientes (nombre, email, telefono, idUser, salario, horasSemanales, sede, empresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiiiss", $nombre, $email, $telefono, $idUser, $salario, $horasSemanales, $sede, $empresa);
    
            if (!$stmt->execute()) {
                error_log("🔴 ERROR en la inserción de cliente: " . $stmt->error);
            } else {
                $inserted++;
            }
        }
        echo json_encode(["success" => true, "message" => "$inserted clientes importados con éxito."]);
    }
  
}
$conn->close();
?>
