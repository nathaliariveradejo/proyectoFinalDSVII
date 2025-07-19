<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Colaborador.php';

$model = new Colaborador();

$id = $_GET['id'] ?? null;
$colaborador = null;

if ($id) {
    $colaborador = $model->findById($id);
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $identificacion = $_POST['identificacion'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $foto = ''; // Aquí podrías procesar subida de archivo si deseas

    if ($id) {
        $resultado = $model->actualizar($id, $nombre, $apellido, $identificacion, $foto, $ubicacion, $telefono, $correo);
    } else {
        $resultado = $model->guardar($nombre, $apellido, $identificacion, $foto, $ubicacion, $telefono, $correo);
    }

    if ($resultado === true) {
        header("Location: colaboradores_listar.php");
        exit;
    } else {
        $mensaje = $resultado;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Editar' : 'Nuevo' ?> Colaborador</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<?php include __DIR__ . '/menu.php'; ?>

<div class="dashboard-container">
    <h2><?= $id ? 'Editar' : 'Registrar' ?> Colaborador</h2>

    <?php if ($mensaje): ?>
            <p style="color: red;"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required value="<?= htmlspecialchars($colaborador['nombre'] ?? '') ?>"><br><br>

        <label>Apellido:</label><br>
        <input type="text" name="apellido" required value="<?= htmlspecialchars($colaborador['apellido'] ?? '') ?>"><br><br>

        <label>Identificación:</label><br>
        <input type="text" name="identificacion" required value="<?= htmlspecialchars($colaborador['identificacion'] ?? '') ?>"><br><br>

        <label>Ubicación:</label><br>
        <input type="text" name="ubicacion" value="<?= htmlspecialchars($colaborador['ubicacion'] ?? '') ?>"><br><br>

        <label>Teléfono:</label><br>
        <input type="text" name="telefono" value="<?= htmlspecialchars($colaborador['telefono'] ?? '') ?>"><br><br>

        <label>Correo:</label><br>
        <input type="email" name="correo" value="<?= htmlspecialchars($colaborador['correo'] ?? '') ?>"><br><br>

        <button type="submit">Guardar</button>
        <a href="colaboradores_listar.php">Cancelar</a>
    </form>
</div>
</body>
</html>
