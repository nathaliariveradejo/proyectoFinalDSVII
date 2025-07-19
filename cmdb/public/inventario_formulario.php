<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Inventario.php';

$model = new Inventario();
$item = [
    'id' => '',
    'nombreEquipo' => '',
    'marca' => '',
    'serie' => '',
    'fechaIngreso' => date('Y-m-d'),
    'costo' => '',
    'depreciacionMeses' => '',
    'imagen' => '',
    'thumbnail' => '',
    'estado' => 'disponible'
];

$modo = 'nuevo';

if (isset($_GET['id'])) {
    $item = $model->findById($_GET['id']);
    $modo = 'editar';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $modo === 'editar' ? 'Editar Inventario' : 'Nuevo Inventario' ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<?php include __DIR__ . '/menu.php'; ?>

<div class="dashboard-container">
    <h2><?= $modo === 'editar' ? 'Editar Equipo' : 'Nuevo Equipo' ?></h2>

    <form action="inventario_procesar.php" method="post">
        <?php if ($modo === 'editar'): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
        <?php endif; ?>

        <label>Nombre del Equipo:</label>
        <input type="text" name="nombreEquipo" required value="<?= htmlspecialchars($item['nombreEquipo']) ?>"><br>

        <label>Marca:</label>
        <input type="text" name="marca" value="<?= htmlspecialchars($item['marca']) ?>"><br>

        <label>Serie:</label>
        <input type="text" name="serie" value="<?= htmlspecialchars($item['serie']) ?>"><br>

        <label>Fecha de Ingreso:</label>
        <input type="date" name="fechaIngreso" value="<?= htmlspecialchars($item['fechaIngreso']) ?>"><br>

        <label>Costo:</label>
        <input type="number" step="0.01" name="costo" value="<?= htmlspecialchars($item['costo']) ?>"><br>

        <label>Depreciación (meses):</label>
        <input type="number" name="depreciacionMeses" value="<?= htmlspecialchars($item['depreciacionMeses']) ?>"><br>

        <?php if ($modo === 'editar'): ?>
                <label>Estado:</label>
                <select name="estado">
                    <option value="disponible" <?= $item['estado'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                    <option value="descartado" <?= $item['estado'] === 'descartado' ? 'selected' : '' ?>>Descartado</option>
                </select><br>
        <?php endif; ?>

        <button type="submit"><?= $modo === 'editar' ? 'Actualizar' : 'Guardar' ?></button>
    </form>
</div>
</body>
</html>
