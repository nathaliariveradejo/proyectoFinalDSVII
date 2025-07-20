<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Inventario.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$model = new Inventario();
$categoria = $_GET['categoria'] ?? '';

if ($categoria) {
    $equipos = $model->filtrarPorCategoria($categoria);
} else {
    $equipos = $model->listar();
}

// Crear hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Inventario');

// Encabezados
$encabezados = ['ID', 'Equipo', 'Marca', 'Serie', 'Ingreso', 'Costo', 'Estado', 'Categoría'];
$col = 'A';
foreach ($encabezados as $titulo) {
    $sheet->setCellValue($col . '1', $titulo);
    $col++;
}

// Datos
$fila = 2;
foreach ($equipos as $equipo) {
    $sheet->setCellValue("A$fila", $equipo['id']);
    $sheet->setCellValue("B$fila", $equipo['nombreEquipo']);
    $sheet->setCellValue("C$fila", $equipo['marca']);
    $sheet->setCellValue("D$fila", $equipo['serie']);
    $sheet->setCellValue("E$fila", $equipo['fechaIngreso']);
    $sheet->setCellValue("F$fila", $equipo['costo']);
    $sheet->setCellValue("G$fila", $equipo['estado']);
    $sheet->setCellValue("H$fila", $equipo['categoria']);
    $fila++;
}

// Descargar
$nombreArchivo = 'inventario_' . ($categoria ? $categoria . '_' : 'completo_') . date('Ymd_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
