<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Colaborador.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud de Equipo - TechSolutions</title>
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
            max-width: 700px;
            margin: 2rem auto;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light);
        }
        
        .section-title h2 {
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Form Styles */
        .request-form {
            margin-top: 20px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-group label i {
            width: 20px;
            color: var(--primary);
        }
        
        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            background-color: #f9f9f9;
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 1em;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.2);
            background-color: var(--white);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--primary);
            color: var(--white);
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 136, 229, 0.3);
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f5f5f5;
            color: #666;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-cancel:hover {
            background: #e0e0e0;
            color: #333;
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
            
            .form-actions {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .dashboard-container {
                padding: 20px;
                margin: 1rem auto;
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
                <h2><i class="fas fa-laptop-medical"></i> Nueva Solicitud de Equipo</h2>
            </div>
            
            <form action="necesidades_procesar.php" method="post" class="request-form">
                <div class="form-group">
                    <label for="colaborador_id"><i class="fas fa-user-tie"></i> Colaborador:</label>
                    <select name="colaborador_id" id="colaborador_id" class="form-control" required>
                        <option value="">-- Selecciona un colaborador --</option>
                        <?php
                            $colModel = new Colaborador();
                            foreach ($colModel->listarActivos() as $c): ?>
                                <option value="<?= $c['id'] ?>">
                                    <?= htmlspecialchars($c['nombre'].' '.$c['apellido']) ?>
                                </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="descripcion"><i class="fas fa-file-alt"></i> Descripción de la necesidad:</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" required 
                        placeholder="Detalle qué equipo se solicita, incluyendo especificaciones técnicas si son necesarias..."></textarea>
                </div>
                
                <div class="form-actions">
                    <a href="necesidades_listar.php" class="btn-cancel">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
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