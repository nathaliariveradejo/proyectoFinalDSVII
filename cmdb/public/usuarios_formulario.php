<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Usuario.php';

$model = new Usuario();
$usuario = ['id' => '', 'nombre' => '', 'correo' => ''];
$modo = 'nuevo';

if (isset($_GET['id'])) {
    $usuario = $model->findById($_GET['id']);
    $modo = 'editar';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $modo === 'editar' ? 'Editar Usuario' : 'Nuevo Usuario' ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <?php include __DIR__ . '/menu.php'; ?>
    <div class="dashboard-container">
        <h2><?= $modo === 'editar' ? 'Editar Usuario' : 'Nuevo Usuario' ?></h2>
        <form method="post" action="usuarios_procesar.php">
            <?php if ($modo === 'editar'): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
            <?php endif; ?>

            <label>Nombre:</label><br>
            <input type="text" name="nombre" required value="<?= htmlspecialchars($usuario['nombre']) ?>"><br><br>

            <label>Correo:</label><br>
            <input type="email" name="correo" required value="<?= htmlspecialchars($usuario['correo']) ?>"><br><br>

            <button type="submit"><?= $modo === 'editar' ? 'Actualizar' : 'Guardar' ?></button>
        </form>
    </div>
</body>
</html>
