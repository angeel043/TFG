<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php'; // Cargar las librerías de Composer
require '../src/database.php'; // Conexión a la BBDD

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use FPDF;

// Mapeo de roles
$roles = [
    0 => "Administrador",
    1 => "Usuario",
    2 => "Supervisor",
    3 => "Equipo Técnico"
];


$conn = Database::getConnection();

// 📌 Recibir los parámetros del fetch
$type = $_POST['type'] ?? ''; // "clientes" o "usuarios"
$format = $_POST['format'] ?? ''; // "pdf" o "excel"

// 📌 Verificar que los parámetros sean válidos
if (!in_array($type, ['clientes', 'usuarios']) || !in_array($format, ['pdf', 'excel'])) {
    die("Error: Parámetros inválidos.");
}

// 📌 Definir la consulta SQL según el tipo de datos
if ($type === 'clientes') {
    $query = "SELECT id, nombre, email, telefono, empresa, sede, salario, horasSemanales FROM clientes";
    $headers = ["ID", "Nombre", "Correo", "Teléfono", "Empresa", "Sede", "Salario", "Horas"];
} else {
    $query = "SELECT id, nombre_usuario AS nombre, email, rol FROM usuarios";
    $headers = ["ID", "Nombre", "Correo", "Rol"];
}

$result = $conn->query($query);

// 📌 Evitar cualquier salida antes de exportar
if (ob_get_length()) ob_end_clean();
header("Content-Encoding: None", true);
header("Pragma: no-cache");
header("Expires: 0");

// 📌 Exportar a EXCEL
if ($format === 'excel') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle(ucfirst($type));

    // 🔹 Aplicar negrita y centrado a los encabezados
    $headerStyle = [
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ];

    // Agregar encabezados
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $sheet->getStyle($col . '1')->applyFromArray($headerStyle); // Aplicar estilo de negrita
        $col++;
    }

    // Agregar datos
    $row = 2;
    while ($data = $result->fetch_assoc()) {
        $col = 'A';
        foreach ($data as $key => $value) {
            // 🔹 Si es la columna "rol", convertir el número a texto
            if ($key === 'rol') {
                $value = $roles[$value] ?? "Desconocido"; // Si no está en el array, poner "Desconocido"
            }

            // 🔹 Si es teléfono, formatearlo como texto para evitar notación científica
            if ($key === 'telefono') {
                $sheet->setCellValueExplicit($col . $row, $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            } else {
                $sheet->setCellValue($col . $row, $value);
            }

            $col++;
        }
        $row++;
    }


    // Configurar encabezados de descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $type . '.xlsx"');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit; // ⬅ Salimos para evitar salida extra
}

// 📌 Exportar a PDF (En horizontal)
if ($format === 'pdf') {
    $pdf = new FPDF('L', 'mm', 'A4'); // 📄 'L' = Landscape (horizontal)
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(270, 10, "Lista de " . ucfirst($type), 0, 1, 'C'); // 📌 Título centrado
    $pdf->Ln(5);

    // 🔹 Aplicar negrita en los encabezados
    $pdf->SetFont('Arial', 'B', 10);
    $widths = [15, 40, 50, 30, 40, 30, 30, 25]; // Anchos de las columnas (ajústalos si es necesario)

    foreach ($headers as $i => $header) {
        $pdf->Cell($widths[$i], 10, utf8_decode($header), 1, 0, 'C');
    }
    $pdf->Ln();

    // Datos
    $pdf->SetFont('Arial', '', 10);
    while ($data = $result->fetch_assoc()) {
        $colIndex = 0;
        foreach ($data as $key => $value) {
            // 🔹 Si es la columna "rol", convertir el número a texto
            if ($key === 'rol') {
                $value = $roles[$value] ?? "Desconocido";
            }

            // Imprimir los valores en la celda correspondiente
            $pdf->Cell($widths[$colIndex], 10, utf8_decode($value), 1, 0, 'C');
            $colIndex++;
        }
        $pdf->Ln();
    }


    // Configurar encabezados de descarga
    header("Content-Type: application/pdf");
    header('Content-Disposition: attachment; filename="' . $type . '.pdf"');

    $pdf->Output("D", $type . ".pdf");
    exit; // ⬅ Salimos para evitar salida extra
}
?>
