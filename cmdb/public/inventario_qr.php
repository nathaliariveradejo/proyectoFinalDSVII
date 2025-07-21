<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Inventario.php';
require __DIR__ . '/../vendor/autoload.php'; 

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID no especificado.');
}

$model = new Inventario();
$equipo = $model->findById($id);

if (!$equipo) {
    die('Equipo no encontrado.');
}

$textoQR = "Nombre: {$equipo['nombreEquipo']}\n";
$textoQR .= "Serie: {$equipo['serie']}\n";
$textoQR .= "Precio: $" . number_format($equipo['costo'], 2) . "\n";
$textoQR .= "Fecha Ingreso: {$equipo['fechaIngreso']}";

$result = Builder::create()
    ->writer(new PngWriter())
    ->data($textoQR)
    ->size(300)
    ->margin(10)
    ->build();


$qrBase64 = base64_encode($result->getString());
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>QR del equipo</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding-top: 50px;
        }

        img {
            border: 5px solid #1e88e5;
            padding: 10px;
            background-color: white;
        }

        h1 {
            color: #1e88e5;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #1e88e5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <h1>QR de <?= htmlspecialchars($equipo['nombreEquipo']) ?></h1>
    <img src="data:image/png;base64,<?= $qrBase64 ?>" alt="Código QR">
    <a href="javascript:window.close();">Cerrar</a>
</body>

</html>