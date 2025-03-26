<?php
require '../../vendor/autoload.php';
require_once '../../vendor/setasign/fpdf/fpdf.php';
require_once __DIR__ . '/../utils/logger.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$roles = [
    0 => "Administrador",
    1 => "Usuario",
    2 => "Supervisor",
    3 => "Equipo Técnico"
];

/***********************************************
* 
* Funcion que prepara los datos a exportar y llama
* a la funcion correspondiente dependiendo del formato
* del archivo a exportar
* 
***********************************************/
function exportData($conn, $type, $format, $idUser) {
    if (!$type || !$format || !in_array($type, ['clientes', 'usuarios']) || !in_array($format, ['pdf', 'excel'])) {
        echo json_encode(["success" => false, "message" => "Tipo o formato inválido"]);
        exit;
    }

    if ($type === 'clientes') {
        $query = "SELECT id, nombre, email, telefono, empresa, sede, salario, horasSemanales FROM clientes";
        $headers = ["ID", "Nombre", "Correo", "Teléfono", "Empresa", "Sede", "Salario", "Horas"];
    } elseif ($type === 'usuarios') {
        $query = "SELECT id, nombre_usuario AS nombre, email, rol FROM usuarios";
        $headers = ["ID", "Nombre", "Correo", "Rol"];
    }

    $result = $conn->query($query);

    $logId = null;
    if ($type === 'usuarios' && $format === 'pdf') $logId = 7;
    elseif ($type === 'usuarios' && $format === 'excel') $logId = 8;
    elseif ($type === 'clientes' && $format === 'pdf') $logId = 9;
    elseif ($type === 'clientes' && $format === 'excel') $logId = 10;

    if ($format === 'excel') {
        exportarExcel($type, $headers, $result, $idUser, $logId, $conn);
    } elseif ($format === 'pdf') {
        exportarPDF($type, $headers, $result, $idUser, $logId, $conn);
    }
}

/***********************************************
* 
* Funcion que exporta los datos de los usuarios o
* clientes en formato excel
* 
***********************************************/
function exportarExcel($type, $headers, $result, $idUser, $logId, $conn) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle(ucfirst($type));

    $headerStyle = [
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
        $col++;
    }

    $row = 2;
    while ($data = $result->fetch_assoc()) {
        $col = 'A';
        foreach ($data as $key => $value) {
            if ($key === 'rol') {
                global $roles;
                $value = $roles[$value] ?? "Desconocido";
            }
            if ($key === 'telefono') {
                $sheet->setCellValueExplicit($col . $row, $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            } else {
                $sheet->setCellValue($col . $row, $value);
            }
            $col++;
        }
        $row++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $type . '.xlsx"');

    registrarLog([$logId, $idUser]);

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

/***********************************************
* 
* Funcion que exporta los datos de los usuarios o
* clientes en formato pdf
* 
***********************************************/
function exportarPDF($type, $headers, $result, $idUser, $logId, $conn) {
    $pdf = new \FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(270, 10, "Lista de " . ucfirst($type), 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 10);
    $widths = [15, 40, 50, 30, 40, 30, 30, 25];

    foreach ($headers as $i => $header) {
        $pdf->Cell($widths[$i], 10, utf8_decode($header), 1, 0, 'C');
    }
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 10);
    while ($data = $result->fetch_assoc()) {
        $colIndex = 0;
        foreach ($data as $key => $value) {
            if ($key === 'rol') {
                global $roles;
                $value = $roles[$value] ?? "Desconocido";
            }
            $pdf->Cell($widths[$colIndex], 10, utf8_decode($value), 1, 0, 'C');
            $colIndex++;
        }
        $pdf->Ln();
    }

    header("Content-Type: application/pdf");
    header('Content-Disposition: attachment; filename="' . $type . '.pdf"');

    registrarLog([$logId, $idUser]);

    $pdf->Output("D", $type . ".pdf");
    exit;
}

?>
