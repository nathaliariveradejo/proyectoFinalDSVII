<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Usuario.php';

$model = new Usuario();
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$cedula = $_POST['cedula'] ?? '';
$id = $_POST['id'] ?? null;


if ($id) {
    $resultado = $model->actualizar($id, $nombre, $correo, $cedula);
} else {
    $resultado = $model->guardar($nombre, $correo, $cedula);
}


header('Location: usuarios_listar.php');
exit;