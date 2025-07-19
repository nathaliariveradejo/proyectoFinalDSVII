<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../models/Colaborador.php';

$model = new Colaborador();
$colaboradores = $model->listarActivos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colaboradores - CMDB</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        :root {
            --primary-blue: #1e88e5;
            --light-blue: #90caf9;
            --dark-blue: #0d47a1;
            --primary-green: #66bb6a;
            --light-green: #a5d6a7;
            --background-light: #f0f8ff;
            --background-white: #ffffff;
            --text-dark: #263238;
            --text-light: #eceff1;
            --error-color: #e53935;
            --success-color: #43a047;
            --table-header: #e3f2fd;
            --table-row-even: #f5f5f5;
            --table-row-hover: #e1f5fe;
        }

        .dashboard-container {
            background-color: var(--background-white);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin: 2rem auto;
            max-width: 1200px;
        }

        .table-listar {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .table-listar th, .table-listar td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .table-listar th {
            background-color: var(--table-header);
            color: var(--dark-blue);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table-listar tr:nth-child(even) {
            background-color: var(--table-row-even);
        }

        .table-listar tr:hover {
            background-color: var(--table-row-hover);
        }

        .actions {
            display: flex;
            gap: 0.8rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-edit {
            background-color: var(--light-blue);
            color: var(--dark-blue);
        }

        .btn-edit:hover {
            background-color: var(--primary-blue);
            color: white;
        }

        .btn-delete {
            background-color: #ffcdd2;
            color: var(--error-color);
        }

        .btn-delete:hover {
            background-color: var(--error-color);
            color: white;
        }

        .btn-add {
            background-color: var(--light-green);
            color: var(--success-color);
            margin-bottom: 1.5rem;
        }

        .btn-add:hover {
            background-color: var(--primary-green);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        .empty-state p {
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .table-listar {
                display: block;
                overflow-x: auto;
            }
            
            .actions {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/menu.php'; ?>

    <div class="dashboard-container">
        <h2>Listado de Colaboradores</h2>
        
        <a href="colaboradores_formulario.php" class="btn btn-add">➕ Añadir Colaborador</a>

        <?php if (empty($colaboradores)): ?>
            <div class="empty-state">
                <p>No hay colaboradores activos en el sistema.</p>
                <a href="colaboradores_formulario.php" class="btn btn-add">➕ Añadir Primer Colaborador</a>
            </div>
        <?php else: ?>
            <table class="table-listar">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Identificación</th>
                        <th>Ubicación</th>
                        <th>Teléfono</th>
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
                            <td class="actions">
                                <a href="colaboradores_formulario.php?id=<?= $c['id'] ?>" class="btn btn-edit">✏️ Editar</a>
                                <a href="colaboradores_borrar.php?id=<?= $c['id'] ?>" 
                                   class="btn btn-delete"
                                   onclick="return confirm('¿Estás seguro de dar de baja este colaborador?')">🗑️ Baja</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>