<?php

// Este archivo lo usamos para dar de baja a un colaborador desde el listado

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Colaborador.php';

// Si no se recibe el ID por la URL, redirigimos al listado
if (!isset($_GET['id'])) {
    header("Location: colaboradores_listar.php");
    exit;
}

// Si hay un ID, llamamos al modelo y ejecutamos la baja lógica
$model = new Colaborador();
$model->bajaLogica($_GET['id']);

// Luego redirigimos al listado de colaboradores
header("Location: colaboradores_listar.php");
exit;
