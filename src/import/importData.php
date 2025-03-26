<?php
require_once '../../vendor/autoload.php'; 
require_once '../utils/logger.php'; 
use PhpOffice\PhpSpreadsheet\IOFactory;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/***********************************************
* 
* Funcion que coge datos de los usuarios a traves de 
* un excel y los introduce en la BBDD creando nuevas entradas
* 
***********************************************/
function importUsers($conn, $idUser) {
    if (!isset($_FILES['file'])) {
        echo json_encode(["success" => false, "message" => "No se ha enviado un archivo."]);
        exit;
    }

    $allowedExtensions = ["xlsx", "csv"];
    $fileExtension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(["success" => false, "message" => "Formato no permitido. Usa .xlsx o .csv"]);
        exit;
    }

    $tmpFilePath = $_FILES["file"]["tmp_name"];
    $spreadsheet = IOFactory::load($tmpFilePath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    if (!$data) {
        echo json_encode(["success" => false, "message" => "Archivo vacío o incorrecto."]);
        exit;
    }

    $inserted = 0;
    foreach ($data as $index => $row) {
        if (empty(trim($row[0]))) continue; 

        [$nombre_usuario, $email, $password, $rol] = $row;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nombre_usuario, $email, $hashedPassword, $rol);

        if ($stmt->execute()) {
            $inserted++;
        }
    }

    if ($inserted > 0) {
        registrarLog([11, $idUser, $inserted]); // Acción 11 = Importar usuarios
    }

    echo json_encode(["success" => true, "message" => "$inserted usuarios importados con éxito."]);
    exit;
}

/***********************************************
* 
* Funcion que coge datos de los clientes a traves de 
* un excel y los introduce en la BBDD creando nuevas entradas
* 
***********************************************/
function importClients($conn, $idUser) {
    if (!isset($_FILES['file'])) {
        echo json_encode(["success" => false, "message" => "No se ha enviado un archivo."]);
        exit;
    }

    $allowedExtensions = ["xlsx", "csv"];
    $fileExtension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(["success" => false, "message" => "Formato no permitido. Usa .xlsx o .csv"]);
        exit;
    }

    $tmpFilePath = $_FILES["file"]["tmp_name"];
    $spreadsheet = IOFactory::load($tmpFilePath);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    if (!$data) {
        echo json_encode(["success" => false, "message" => "Archivo vacío o incorrecto."]);
        exit;
    }

    $inserted = 0;
    foreach ($data as $index => $row) {
        if (empty(trim($row[0]))) continue;

        [$nombre, $email, $telefono, $idUserAsignado, $salario, $horasSemanales, $sede, $empresa] = $row;

        $idUserAsignado = !empty($idUserAsignado) ? intval($idUserAsignado) : NULL;
        $salario = !empty($salario) ? intval($salario) : 0;
        $horasSemanales = !empty($horasSemanales) ? intval($horasSemanales) : 0;

        $stmt = $conn->prepare("INSERT INTO clientes (nombre, email, telefono, idUser, salario, horasSemanales, sede, empresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiiiss", $nombre, $email, $telefono, $idUserAsignado, $salario, $horasSemanales, $sede, $empresa);

        if ($stmt->execute()) {
            $inserted++;
        }
    }

    if ($inserted > 0) {
        registrarLog([12, $idUser, $inserted]); // Acción 12 = Importar clientes
    }

    echo json_encode(["success" => true, "message" => "$inserted clientes importados con éxito."]);
    exit;
}

?>
