<?php

// Este archivo se encarga de mostrar el listado de todos los colaboradores activos en el sistema.
// Se llama al modelo de colaboradores y se obtienen los datos necesarios para mostrarlos

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Colaborador.php';
require_once __DIR__ . '/../models/Inventario.php';

$model = new Colaborador();
$colaboradores = $model->listarActivos();
$inventarioModel = new Inventario();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colaboradores - TechSolutions</title>
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
        
        /* Dashboard Content */
        .dashboard {
            padding: 30px 0;
        }
        
        .dashboard-container {
            background: var(--white);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }
        
        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light);
        }
        
        .section-title h2 {
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Botones */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
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
        
        .btn-success {
            background: var(--success);
            color: var(--white);
        }
        
        .btn-success:hover {
            background: #388e3c;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Tabla con Scroll Horizontal */
        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
            padding: 2px;
        }
        
        .table-listar {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            font-size: 0.95rem;
            min-width: 1400px; /* Ancho mínimo para garantizar scroll */
        }
        
        .table-listar th, .table-listar td {
            padding: 1.2rem 1rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
            white-space: nowrap;
        }
        
        .table-listar th {
            background-color: var(--light);
            color: var(--dark);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
        }
        
        .table-listar tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .table-listar tr:hover {
            background-color: #f0f7ff;
        }
        
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: nowrap;
            justify-content: flex-start;
        }
        
        .btn-sm {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }
        
        .btn-edit {
            background: #e3f2fd;
            color: var(--primary);
            border: 1px solid #bbdefb;
        }
        
        .btn-edit:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .btn-delete {
            background: #ffebee;
            color: var(--error);
            border: 1px solid #ffcdd2;
        }
        
        .btn-delete:hover {
            background: var(--error);
            color: white;
            border-color: var(--error);
        }
        
        .btn-assign {
            background: #e8f5e9;
            color: var(--success);
            border: 1px solid #c8e6c9;
        }
        
        .btn-assign:hover {
            background: var(--success);
            color: white;
            border-color: var(--success);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
            background: #f9f9f9;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .empty-state p {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }
        
        .foto-colaborador {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
            border: 1px solid #ddd;
        }
        
        .avatar-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
        }
        
        .avatar-placeholder i {
            color: #ccc;
            font-size: 24px;
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
        @media (max-width: 900px) {
            .menu-horizontal ul {
                flex-wrap: wrap;
            }
        }
        
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .logo {
                margin-bottom: 10px;
            }
            
            .actions {
                flex-direction: column;
                gap: 8px;
            }
        }
        
        @media (max-width: 576px) {
            .menu-horizontal ul {
                flex-direction: column;
                align-items: center;
            }
            
            .menu-horizontal a {
                padding: 12px 20px;
            }
            
            .dashboard-container {
                padding: 20px;
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
    
    <!-- Dashboard Content -->
    <div class="dashboard container">
        <div class="dashboard-container">
            <div class="section-title">
                <h2><i class="fas fa-users"></i> Listado de Colaboradores</h2>
                <a href="colaboradores_formulario.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Añadir Colaborador
                </a>
            </div>

            <?php if (empty($colaboradores)): ?>
                <div class="empty-state">
                    <p>No hay colaboradores activos en el sistema.</p>
                    <a href="colaboradores_formulario.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Añadir Primer Colaborador
                    </a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table-listar">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Identificación</th>
                                <th>Ubicación</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Foto</th>
                                <th>Teléfono Asignado</th>
                                <th>Computadora Asignada</th>
                                <th>Red Asignada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($colaboradores as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['id']) ?></td>
                                    <td><?= htmlspecialchars($c['nombre']) ?></td>
                                    <td><?= htmlspecialchars($c['apellido']) ?></td>
                                    <td><?= htmlspecialchars($c['identificacion']) ?></td>
                                    <td><?= htmlspecialchars($c['ubicacion']) ?></td>
                                    <td><?= htmlspecialchars($c['telefono']) ?></td>
                                    <td><?= htmlspecialchars($c['correo']) ?></td>
                                    <td>
                                        <?php if (!empty($c['foto'])): ?>
                                            <img src="img/<?= htmlspecialchars($c['foto']) ?>"
                                                alt="Foto de <?= htmlspecialchars($c['nombre']) ?>" 
                                                class="foto-colaborador">
                                        <?php else: ?>
                                            <div class="avatar-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <?php
                                    // Inicializo con 'Ninguno'
                                    $telefono    = 'Ninguno';
                                    $computadora = 'Ninguno';
                                    $red         = 'Ninguno';

                                    // Recorro todos los equipos asignados a este colaborador
                                    $equipos = $inventarioModel->obtenerEquiposPorColaborador($c['id']);
                                    foreach ($equipos as $e) {
                                        switch ($e['tipo_equipo']) {
                                            case 'telefono':
                                                $telefono = $e['nombreEquipo'];
                                                break;
                                            case 'computadora':
                                                $computadora = $e['nombreEquipo'];
                                                break;
                                            case 'red':
                                                $red = $e['nombreEquipo'];
                                                break;
                                        }
                                    }
                                    ?>
                                    <td><?= htmlspecialchars($telefono) ?></td>
                                    <td><?= htmlspecialchars($computadora) ?></td>
                                    <td><?= htmlspecialchars($red) ?></td>


                                    <td>
                                        <div class="actions">
                                            <a href="colaboradores_formulario.php?id=<?= $c['id'] ?>" class="btn-sm btn-edit">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <a href="colaboradores_borrar.php?id=<?= $c['id'] ?>" class="btn-sm btn-delete"
                                                onclick="return confirm('¿Estás seguro de dar de baja este colaborador?')">
                                                <i class="fas fa-trash-alt"></i> Baja
                                            </a>
                                            <a href="asignar_equipo.php?id_colaborador=<?= $c['id'] ?>" class="btn-sm btn-assign">
                                                <i class="fas fa-laptop"></i> Asignar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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