<?php

// Este archivo lo usamos para asignar equipos (computadora, teléfono y red) a un colaborador.
// Se busca al colaborador por su ID, listamos los equipos disponibles por tipo,
// y mostramos un formulario con selectores para asignarlos.
// Al enviar el formulario, se asignan los equipos seleccionados y redirige al listado de colaboradores.

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Colaborador.php';
require_once __DIR__ . '/../models/Inventario.php';

$colaboradorModel = new Colaborador();
$inventarioModel = new Inventario();

$id_colaborador = $_GET['id_colaborador'] ?? null;
$colaborador = null;
$mensaje = "";

if ($id_colaborador) {
    $colaborador = $colaboradorModel->findById($id_colaborador);
}

$computadorasDisponibles = $inventarioModel->listarDisponiblesPorTipo('computadora');
$telefonosDisponibles = $inventarioModel->listarDisponiblesPorTipo('telefono');
$redesDisponibles = $inventarioModel->listarDisponiblesPorTipo('red');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $computadora_id = $_POST['computadora_id'] ?? null;
    $telefono_id = $_POST['telefono_id'] ?? null;
    $red_id = $_POST['red_id'] ?? null;

    if ($id_colaborador) {
        if ($computadora_id)
            $inventarioModel->asignarEquipo($computadora_id, $id_colaborador);
        if ($telefono_id)
            $inventarioModel->asignarEquipo($telefono_id, $id_colaborador);
        if ($red_id)
            $inventarioModel->asignarEquipo($red_id, $id_colaborador);

        header("Location: colaboradores_listar.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Equipos - TechSolutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        :root {
            --primary: #1e88e5;
            --primary-dark: #1565c0;
            --secondary: #0d47a1;
            --light: #e3f2fd;
            --dark: #1a237e;
            --gray: #f5f5f5;
            --text: #333;
            --white: #ffffff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --success: #43a047;
            --error: #e53935;
            --warning: #ff9800;
        }
        
        body {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            color: var(--text);
            min-height: 100vh;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header Styles */
        header {
            background: linear-gradient(to right, var(--primary), var(--dark));
            color: var(--white);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo-icon {
            font-size: 28px;
            background: var(--white);
            color: var(--primary);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 700;
        }
        
        .logo-text span {
            font-weight: 300;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            padding: 8px 15px;
            border-radius: 30px;
            color: var(--white);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        /* Menu Horizontal */
        .menu-horizontal {
            background: var(--white);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .menu-horizontal ul {
            display: flex;
            list-style: none;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .menu-horizontal li {
            padding: 0;
        }
        
        .menu-horizontal a {
            display: block;
            padding: 18px 25px;
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
        }
        
        .menu-horizontal a:hover, .menu-horizontal a.active {
            color: var(--primary);
            background: rgba(30, 136, 229, 0.05);
        }
        
        .menu-horizontal a.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary);
        }
        
        /* Form Container */
        .form-container {
            background: var(--white);
            border-radius: 15px;
            padding: 30px;
            margin: 30px auto;
            box-shadow: var(--shadow);
            max-width: 800px;
        }
        
        .form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light);
        }
        
        .form-header h2 {
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-header i {
            color: var(--primary);
            font-size: 1.5em;
        }
        
        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .equipo-section {
            background: var(--light);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .equipo-section h3 {
            color: var(--dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: var(--white);
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.2);
        }
        
        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: var(--text);
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Message Styles */
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .message.error {
            background: rgba(229, 57, 53, 0.1);
            color: var(--error);
            border: 1px solid rgba(229, 57, 53, 0.3);
        }
        
        .colaborador-info {
            background: var(--light);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .colaborador-info p {
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .colaborador-info strong {
            color: var(--dark);
        }
        
        /* Footer */
        footer {
            background: var(--dark);
            color: var(--white);
            padding: 30px 0;
            margin-top: 50px;
        }
        
        .footer-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .footer-section {
            flex: 1;
            min-width: 250px;
        }
        
        .footer-title {
            font-size: 20px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--primary);
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: #bbdefb;
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .footer-links a:hover {
            color: var(--white);
            padding-left: 5px;
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #90caf9;
            font-size: 14px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container container">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-microchip"></i>
                </div>
                <div class="logo-text">Tech<span>Solutions</span></div>
            </div>
            <div class="user-info">
                <button class="logout-btn" onclick="window.location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </button>
            </div>
        </div>
    </header>
    
    <!-- Navigation - Menú horizontal -->
    <?php include __DIR__ . '/menu.php'; ?>
    
    <!-- Form Content -->
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2><i class="fas fa-laptop"></i> Asignar Equipos a Colaborador</h2>
            </div>

            <?php if ($mensaje): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <?php if ($colaborador): ?>
                <div class="colaborador-info">
                    <p><strong>Colaborador:</strong> <?= htmlspecialchars($colaborador['nombre']) . " " . htmlspecialchars($colaborador['apellido']) ?></p>
                    <p><strong>Identificación:</strong> <?= htmlspecialchars($colaborador['identificacion']) ?></p>
                    <p><strong>Ubicación:</strong> <?= htmlspecialchars($colaborador['ubicacion'] ?? 'No especificada') ?></p>
                </div>

                <form method="post">
                    <div class="form-grid">
                        <div class="equipo-section">
                            <h3><i class="fas fa-desktop"></i> Computadora</h3>
                            <select name="computadora_id" class="form-control">
                                <option value="">-- Seleccionar computadora --</option>
                                <?php foreach ($computadorasDisponibles as $equipo): ?>
                                    <option value="<?= $equipo['id'] ?>">
                                        <?= htmlspecialchars($equipo['nombreEquipo']) ?> - <?= htmlspecialchars($equipo['marca']) ?>
                                        (<?= htmlspecialchars($equipo['serie']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="equipo-section">
                            <h3><i class="fas fa-mobile-alt"></i> Teléfono</h3>
                            <select name="telefono_id" class="form-control">
                                <option value="">-- Seleccionar teléfono --</option>
                                <?php foreach ($telefonosDisponibles as $equipo): ?>
                                    <option value="<?= $equipo['id'] ?>">
                                        <?= htmlspecialchars($equipo['nombreEquipo']) ?> - <?= htmlspecialchars($equipo['marca']) ?>
                                        (<?= htmlspecialchars($equipo['serie']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="equipo-section">
                            <h3><i class="fas fa-network-wired"></i> Equipo de Red</h3>
                            <select name="red_id" class="form-control">
                                <option value="">-- Seleccionar equipo de red --</option>
                                <?php foreach ($redesDisponibles as $equipo): ?>
                                    <option value="<?= $equipo['id'] ?>">
                                        <?= htmlspecialchars($equipo['nombreEquipo']) ?> - <?= htmlspecialchars($equipo['marca']) ?>
                                        (<?= htmlspecialchars($equipo['serie']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Asignar Equipos
                        </button>
                        <a href="colaboradores_listar.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <div class="message error">
                    <i class="fas fa-user-slash"></i> Colaborador no encontrado
                </div>
                <a href="colaboradores_listar.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al listado
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="container footer-container">
            <div class="footer-section">
                <h3 class="footer-title">TechSolutions</h3>
                <p>Especialistas en gestión de activos tecnológicos para empresas que buscan optimizar su infraestructura IT.</p>
            </div>
            
            <div class="footer-section">
                <h3 class="footer-title">Enlaces Rápidos</h3>
                <ul class="footer-links">
                    <li><a href="dashboard.php"><i class="fas fa-chevron-right"></i> Inicio</a></li>
                    <li><a href="usuarios_listar.php"><i class="fas fa-chevron-right"></i> Usuarios</a></li>
                    <li><a href="inventario_listar.php"><i class="fas fa-chevron-right"></i> Inventario</a></li>
                    <li><a href="necesidades_listar.php"><i class="fas fa-chevron-right"></i> Solicitudes</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3 class="footer-title">Contacto</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-map-marker-alt"></i> Panamá Norte, PTY</a></li>
                    <li><a href="#"><i class="fas fa-phone"></i> (507) 268-0000</a></li>
                    <li><a href="#"><i class="fas fa-envelope"></i> info@techsolutions.com</a></li>
                    <li><a href="#"><i class="fas fa-clock"></i> Lunes-Viernes: 8am - 5pm</a></li>
                </ul>
            </div>
        </div>
        
        <div class="copyright container">
            &copy; 2025 TechSolutions - Gestión de Equipos Tecnológicos. Todos los derechos reservados.
        </div>
    </footer>
</body>
</html>