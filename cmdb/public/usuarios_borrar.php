<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_GET['id'])) {
    header("Location: usuarios_listar.php");
    exit;
}

$model = new Usuario();
$resultado = $model->bajaLogica($_GET['id']);

session_start();
if ($resultado === true) {
    $_SESSION['exito'] = "El usuario fue dado de baja correctamente.";
} else {
    $_SESSION['error'] = $resultado;
}
header("Location: usuarios_listar.php");
exit;
