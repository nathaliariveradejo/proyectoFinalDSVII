<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Inventario.php';

$model = new Inventario();

$id = $_POST['id'] ?? null;
$nombreEquipo = $_POST['nombreEquipo'] ?? '';
$marca = $_POST['marca'] ?? '';
$serie = $_POST['serie'] ?? '';
$fechaIngreso = $_POST['fechaIngreso'] ?? '';
$costo = $_POST['costo'] ?? 0;
$depreciacionMeses = $_POST['depreciacionMeses'] ?? 0;
$estado = $_POST['estado'] ?? 'disponible';

if ($id) {
    $model->actualizar($id, $nombreEquipo, $marca, $serie, $fechaIngreso, $costo, $estado, $depreciacionMeses);
} else {
    $model->guardar($nombreEquipo, $marca, $serie, $fechaIngreso, $costo, $depreciacionMeses);
}

header("Location: inventario_listar.php");
exit;
