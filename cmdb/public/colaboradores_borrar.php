<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Colaborador.php';

if (!isset($_GET['id'])) {
    header("Location: colaboradores_listar.php");
    exit;
}

$model = new Colaborador();
$model->bajaLogica($_GET['id']);

header("Location: colaboradores_listar.php");
exit;
