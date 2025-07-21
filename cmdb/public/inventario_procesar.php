<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Inventario.php';

$model = new Inventario();

$id = $_POST['id'] ?? null;
$nombreEquipo = $_POST['nombreEquipo'];
$marca = $_POST['marca'];
$serie = $_POST['serie'];
$fechaIngreso = $_POST['fechaIngreso'];
$costo = $_POST['costo'];
$depreciacionMeses = $_POST['depreciacionMeses'];
$categoria = $_POST['categoria'] !== 'otra' ? $_POST['categoria'] : $_POST['nueva_categoria'];
$estado = $_POST['estado'] ?? 'disponible';
$comentario = $_POST['comentario'] ?? null;

$imagen = null;
if (!empty($_FILES['imagen']['name'])) {
    $carpeta = __DIR__ . '/img/';
    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
    $rutaDestino = 'img/' . $nombreArchivo;
    move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . '/' . $rutaDestino);
    $imagen = $nombreArchivo;
}

if ($id) {
    $actual = $model->findById($id);
    if (!$imagen && isset($actual['imagen'])) {
        $imagen = $actual['imagen'];
    }
    $model->actualizar($id, $nombreEquipo, $marca, $serie, $fechaIngreso, $costo, $estado, $depreciacionMeses, $imagen, null, $categoria, $comentario);
} else {
    $model->guardar($nombreEquipo, $marca, $serie, $fechaIngreso, $costo, $depreciacionMeses, $imagen, null, $categoria);
}

header('Location: inventario_listar.php');
exit;
