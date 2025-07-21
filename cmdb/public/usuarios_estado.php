<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_GET['id'])) {
    header("Location: usuarios_listar.php");
    exit;
}

$id = (int) $_GET['id'];

$model = new Usuario();

$usuario = $model->findById($id);

if (!$usuario) {
    $_SESSION['error'] = "Usuario no encontrado.";
    header("Location: usuarios_listar.php");
    exit;
}

// Cambia de estado: si está en 1 pasa a 0, si está en 0 pasa a 1
$nuevoEstado = $usuario['estado'] == 1 ? 0 : 1;

$resultado = $model->cambiarEstado($id, $nuevoEstado);

if ($resultado === true) {
    $_SESSION['exito'] = "El usuario fue " . ($nuevoEstado == 1 ? "activado" : "desactivado") . " correctamente.";
} else {
    $_SESSION['error'] = $resultado;
}

header("Location: usuarios_listar.php");
exit;