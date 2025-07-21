<?php

// Llamamos a la conexión con la BD
require_once 'Conexion.php';

// Creamos esta clase para manejar las asignaciones entre colaboradores y equipos del inventario
class AsignacionInventario {
    private $pdo;

    // Al construir esta clase, nos conectamos con la BD automáticamente
    public function __construct() {
        $this->pdo = Conexion::conectar();
    }

    // Con esta función registramos una nueva asignación de equipo a un colaborador
    public function asignar($colaborador_id, $inventario_id, $tipo_asignacion) {
        $sql = "INSERT INTO colaborador_inventario (colaborador_id, inventario_id, tipo_asignacion)
                VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$colaborador_id, $inventario_id, $tipo_asignacion]);
    }

    // Aquí buscamos todos los equipos asignados a un colaborador
    public function obtenerPorColaborador($colaborador_id) {
        $sql = "SELECT i.*, ci.tipo_asignacion
                FROM colaborador_inventario ci
                JOIN inventario i ON ci.inventario_id = i.id
                WHERE ci.colaborador_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$colaborador_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>