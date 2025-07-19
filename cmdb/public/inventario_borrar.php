<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Inventario.php';

if (!isset($_GET['id'])) {
    header("Location: inventario_listar.php");
    exit;
}

$model = new Inventario();
$model->bajaLogica($_GET['id']);
header("Location: inventario_listar.php");
exit;
