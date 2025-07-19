<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Usuario.php';

$model = new Usuario();
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$id = $_POST['id'] ?? null;

if ($id) {
    $resultado = $model->actualizar($id, $nombre, $correo);
} else {
    $resultado = $model->guardar($nombre, $correo);
}

header('Location: usuarios_listar.php');
exit;
