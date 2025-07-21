<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Inventario.php';

$model = new Inventario();

$id = $_GET['id'] ?? null;
$item = null;

if ($id) {
    $item = $model->findById($id);
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreEquipo = $_POST['nombreEquipo'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $serie = $_POST['serie'] ?? '';
    $fechaIngreso = $_POST['fechaIngreso'] ?? '';
    $costo = $_POST['costo'] ?? '';
    $depreciacionMeses = $_POST['depreciacionMeses'] ?? '';
    $categoria = ($_POST['categoria'] !== 'otra') ? $_POST['categoria'] : $_POST['nueva_categoria'];
    $estado = $_POST['estado'] ?? 'disponible';
    $comentario = $_POST['comentario'] ?? '';
    $tipoEquipo = $_POST['tipo_equipo'] ?? null;
    $imagen = $item['imagen'] ?? '';

    // Procesamos la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $directorioDestino = __DIR__ . '/img/'; // Le damos la ruta
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0755, true);
        }
        $nombreArchivo = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $_FILES['imagen']['name']);
        $rutaDestino = $directorioDestino . $nombreArchivo;
        
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            // Eliminamos la imagen anterior si existe
            if ($id && !empty($item['imagen']) && file_exists($directorioDestino . $item['imagen'])) {
                unlink($directorioDestino . $item['imagen']);
            }
            $imagen = $nombreArchivo;
        }
    }

    // Validar campos obligatorios
    $camposObligatorios = [
        'nombreEquipo' => $nombreEquipo,
        'fechaIngreso' => $fechaIngreso,
        'costo' => $costo,
        'categoria' => $categoria
    ];
    
    $camposVacios = array_filter($camposObligatorios, function($valor) {
        return empty($valor);
    });
    
    if (count($camposVacios)) {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
    } else {
        if ($id) {
            $resultado = $model->actualizar(
                $id, 
                $nombreEquipo, 
                $marca, 
                $serie, 
                $fechaIngreso, 
                $costo, 
                $estado, 
                $depreciacionMeses, 
                $imagen, 
                null, 
                $categoria, 
                $comentario, 
                $tipoEquipo
            );
        } else {
            $resultado = $model->guardar(
                $nombreEquipo, 
                $marca, 
                $serie, 
                $fechaIngreso, 
                $costo, 
                $estado, 
                $depreciacionMeses, 
                $imagen, 
                null, 
                $categoria, 
                $comentario, 
                $tipoEquipo
            );
        }

        if ($resultado === true) {
            header("Location: inventario_listar.php");
            exit;
        } else {
            $mensaje = $resultado;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Editar' : 'Nuevo' ?> Inventario - TechSolutions</title>
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
        
 
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
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
        
        .form-group label.required::after {
            content: ' *';
            color: var(--error);
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
        
        .form-control-file {
            padding: 10px 0;
        }
        
        .image-preview {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .image-preview img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        

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
        
        .message.success {
            background: rgba(67, 160, 71, 0.1);
            color: var(--success);
            border: 1px solid rgba(67, 160, 71, 0.3);
        }
        

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
        

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
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
    
  
    <?php include __DIR__ . '/menu.php'; ?>
    

    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2><i class="fas fa-<?= $id ? 'edit' : 'plus-circle' ?>"></i> <?= $id ? 'Editar' : 'Registrar' ?> Equipo</h2>
            </div>

            <?php if ($mensaje): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $mensaje ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombreEquipo" class="required">Nombre del Equipo</label>
                        <input type="text" id="nombreEquipo" name="nombreEquipo" class="form-control" required
                            value="<?= htmlspecialchars($item['nombreEquipo'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="marca">Marca</label>
                        <input type="text" id="marca" name="marca" class="form-control"
                            value="<?= htmlspecialchars($item['marca'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="serie">Número de Serie</label>
                        <input type="text" id="serie" name="serie" class="form-control"
                            value="<?= htmlspecialchars($item['serie'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="fechaIngreso" class="required">Fecha de Ingreso</label>
                        <input type="date" id="fechaIngreso" name="fechaIngreso" class="form-control" required
                            value="<?= htmlspecialchars($item['fechaIngreso'] ?? date('Y-m-d')) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="costo" class="required">Costo ($)</label>
                        <input type="number" step="0.01" id="costo" name="costo" class="form-control" required
                            value="<?= htmlspecialchars($item['costo'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="depreciacionMeses">Depreciación Mensual ($)</label>
                        <input type="number" step="0.01" id="depreciacionMeses" name="depreciacionMeses" class="form-control"
                            value="<?= htmlspecialchars($item['depreciacionMeses'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_equipo">Tipo de Equipo</label>
                        <select id="tipo_equipo" name="tipo_equipo" class="form-control">
                            <option value="">-- Selecciona --</option>
                            <option value="Computadora" <?= ($item['tipo_equipo'] ?? '') === 'Computadora' ? 'selected' : '' ?>>
                                Computadora</option>
                            <option value="Teléfono" <?= ($item['tipo_equipo'] ?? '') === 'Teléfono' ? 'selected' : '' ?>>
                                Teléfono</option>
                            <option value="Red" <?= ($item['tipo_equipo'] ?? '') === 'Red' ? 'selected' : '' ?>>
                                Red</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="imagen">Imagen del Equipo</label>
                        <input type="file" id="imagen" name="imagen" class="form-control form-control-file" accept="image/*">
                        
                        <?php if (!empty($item['imagen'])): ?>
                            <div class="image-preview">
                                <img src="/img/<?= htmlspecialchars($item['imagen']) ?>" alt="Imagen equipo">
                                <div>Imagen actual</div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="categoria" class="required">Categoría</label>
                        <select id="categoria" name="categoria" class="form-control" onchange="mostrarNuevaCategoria()" required>
                            <option value="Software" <?= ($item['categoria'] ?? '') === 'Software' ? 'selected' : '' ?>>
                                Software</option>
                            <option value="Hardware" <?= ($item['categoria'] ?? '') === 'Hardware' ? 'selected' : '' ?>>
                                Hardware</option>
                            <option value="Equipo de Red" <?= ($item['categoria'] ?? '') === 'Equipo de Red' ? 'selected' : '' ?>>
                                Equipo de Red</option>
                            <option value="Equipo de Cómputo" <?= ($item['categoria'] ?? '') === 'Equipo de Cómputo' ? 'selected' : '' ?>>
                                Equipo de Cómputo</option>
                            <option value="Equipo de Telefonía" <?= ($item['categoria'] ?? '') === 'Equipo de Telefonía' ? 'selected' : '' ?>>
                                Equipo de Telefonía</option>
                            <option value="Licencia" <?= ($item['categoria'] ?? '') === 'Licencia' ? 'selected' : '' ?>>
                                Licencia</option>
                            <option value="otra">Otra categoría...</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="nueva-cat" style="display:none;">
                        <label for="nueva_categoria" class="required">Nombre de la nueva categoría</label>
                        <input type="text" id="nueva_categoria" name="nueva_categoria" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="estado" class="required">Estado</label>
                        <select id="estado" name="estado" class="form-control" onchange="toggleComentario()" required>
                            <option value="disponible" <?= ($item['estado'] ?? '') === 'disponible' ? 'selected' : '' ?>>
                                Disponible</option>
                            <option value="descartado" <?= ($item['estado'] ?? '') === 'descartado' ? 'selected' : '' ?>>
                                Descartado</option>
                            <option value="donado" <?= ($item['estado'] ?? '') === 'donado' ? 'selected' : '' ?>>
                                Donado</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="seccionComentario">
                        <label id="lblComentario">Comentario</label>
                        <textarea id="comentario" name="comentario" class="form-control" rows="4"><?= htmlspecialchars($item['comentario'] ?? '') ?></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="inventario_listar.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
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

    <script>
        function mostrarNuevaCategoria() {
            const sel = document.getElementById('categoria');
            const nuevaCatDiv = document.getElementById('nueva-cat');
            nuevaCatDiv.style.display = sel.value === 'otra' ? 'block' : 'none';
            
            if (sel.value !== 'otra') {
                document.getElementById('nueva_categoria').value = '';
            }
        }
        
        function toggleComentario() {
            const estado = document.getElementById('estado').value;
            const seccionComentario = document.getElementById('seccionComentario');
            const comentario = document.getElementById('comentario');
            const lblComentario = document.getElementById('lblComentario');
            
            if (estado === 'disponible') {
                seccionComentario.style.display = 'none';
                comentario.removeAttribute('required');
            } else {
                seccionComentario.style.display = 'block';
                
                if (estado === 'donado') {
                    lblComentario.textContent = 'Comentario (razón de donación)';
                } else {
                    lblComentario.textContent = 'Comentario Técnico';
                }
            }
        }
        
        // Inicializamos los controles al cargar la página
        window.addEventListener('DOMContentLoaded', () => {
            mostrarNuevaCategoria();
            toggleComentario();
            
            // Validams el formulario antes de enviarlo
            document.querySelector('form').addEventListener('submit', function(e) {
                const categoria = document.getElementById('categoria').value;
                const nuevaCategoria = document.getElementById('nueva_categoria').value;
                
                if (categoria === 'otra' && !nuevaCategoria.trim()) {
                    e.preventDefault();
                    alert('Por favor ingrese el nombre de la nueva categoría');
                    document.getElementById('nueva_categoria').focus();
                }
            });
        });
    </script>
</body>
</html>