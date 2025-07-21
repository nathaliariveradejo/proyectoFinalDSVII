<?php

// Llamamos a la conexión con la BD
require_once 'Conexion.php';

// Creamos esta clase para manejar todo lo relacionado con las necesidades/solicitudes de equipo
class Necesidad
{
    private $pdo;

    // Con esto nos conectamos automáticamente con la BD
    public function __construct()
    {
        $this->pdo = Conexion::conectar();
    }

    // Con esta función listamos todas las solicitudes hechas por los colaboradores
    public function listar()
    {
        $sql = "SELECT ne.id, ne.colaborador_id, c.nombre, c.apellido,
                       ne.descripcion, ne.fecha_solicitud, ne.estado
                  FROM necesidades_equipo ne
             LEFT JOIN colaboradores c ON c.id = ne.colaborador_id
              ORDER BY ne.fecha_solicitud DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Esta función la usamos para cambiar el estado de una necesidad (completada o rechazada)
    public function actualizarEstado($id, $nuevoEstado)
    {
        $sql = "UPDATE necesidades_equipo
                   SET estado = ?
                 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nuevoEstado, $id]);
    }

    // Con esta función guardamos una nueva solicitud de equipo hecha por un colaborador
    public function guardar($colaboradorId, $descripcion)
    {
        $sql = "INSERT INTO necesidades_equipo
                  (colaborador_id, descripcion)
                VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$colaboradorId, $descripcion]);
    }
}

?>