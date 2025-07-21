<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Necesidad.php';

$model = new Necesidad();

// Creamos una nueva solicitud
if (isset($_POST['descripcion'], $_POST['colaborador_id'])) {
    $model->guardar(
      intval($_POST['colaborador_id']),
      trim($_POST['descripcion'])
    );
    header("Location: necesidades_listar.php");
    exit;
}

// Aqui manejamos si se suplieron o no las necesidades/solicitudes
$id     = $_POST['id'] ?? null;
$accion = $_POST['accion'] ?? null;
if ($id && in_array($accion, ['completar','rechazar'], true)) {
    $nuevoEstado = $accion === 'completar' ? 'completada' : 'rechazada';
    $model->actualizarEstado($id, $nuevoEstado);
}

header("Location: necesidades_listar.php");
exit;
