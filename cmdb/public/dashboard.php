<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - CMDB</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <!-- Menú horizontal -->
    <nav class="menu-horizontal">
        <ul>
            <li><a href="dashboard.php">Inicio</a></li>
            <li><a href="usuarios_listar.php">Usuarios</a></li>
            <li><a href="inventario_listar.php">Inventario</a></li>
            <li><a href="colaboradores_listar.php">Colaboradores</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <h2>Bienvenido, <?php echo htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8'); ?></h2>
        <p>Estás dentro del sistema CMDB. Elige una opción del menú para comenzar.</p>
    </div>
</body>
</html>
