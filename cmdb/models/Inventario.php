<?php

// Llamamos a la conexión BD
require_once 'Conexion.php';

// Creamos esta clase para manejar todo lo relacionado al inventario
class Inventario
{
    private $pdo;

    // Al construir esta clase, nos conectamos con la BD automáticamente
    public function __construct()
    {
        $this->pdo = Conexion::conectar();
    }

    // Con esta función listamos todos los equipos y sus detalles
    public function listar()
    {
        $sql = "SELECT
                    i.id,
                    i.nombreEquipo,
                    i.marca,
                    i.serie,
                    i.fechaIngreso,
                    i.costo,
                    i.estado,
                    i.categoria,
                    i.depreciacionMeses,
                    i.comentario,
                    i.imagen,
                    i.thumbnail,
                    i.tipo_equipo,
                    c.nombre AS nombre_colaborador
                FROM inventario i
                LEFT JOIN colaboradores c ON i.id_colaborador = c.id
                ORDER BY i.id ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Aquí buscamos un equipo según su ID
    public function findById($id)
    {
        $sql = "SELECT * FROM inventario WHERE id = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Con esta función guardamos un nuevo equipo en el inventario
    public function guardar(
        $nombreEquipo,
        $marca,
        $serie,
        $fechaIngreso,
        $costo,
        $depreciacionMeses,
        $imagen = null,
        $thumbnail = null,
        $categoria = 'Sin categoría',
        $tipo_equipo = 'computadora'
    ) {
        // Validamos que al menos el nombre del equipo y la fecha de ingreso estén presentes
        if (empty($nombreEquipo) || empty($fechaIngreso)) {
            return "El nombre del equipo y la fecha de ingreso son obligatorios.";
        }

        try {
            $sql = "INSERT INTO inventario
                    (nombreEquipo, marca, serie, fechaIngreso, costo, estado, depreciacionMeses, imagen, thumbnail, categoria, comentario, tipo_equipo)
                    VALUES (?, ?, ?, ?, ?, 'disponible', ?, ?, ?, ?, NULL, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $nombreEquipo,
                $marca,
                $serie,
                $fechaIngreso,
                $costo,
                $depreciacionMeses,
                $imagen,
                $thumbnail,
                $categoria,
                $tipo_equipo
            ]);
            return true;
        } catch (PDOException $e) {
            return "Error al guardar inventario: " . $e->getMessage();
        }
    }

    // Aquí actualizamos los datos de un equipo
    public function actualizar(
        $id,
        $nombreEquipo,
        $marca,
        $serie,
        $fechaIngreso,
        $costo,
        $estado,
        $depreciacionMeses,
        $imagen = null,
        $thumbnail = null,
        $categoria = 'Sin categoría',
        $comentario = null,
        $tipo_equipo = 'computadora'
    ) {
        try {
            // Buscamos si el equipo ya tenía colaborador, para mantenerlo si sigue asignado
            $colaboradorPrevio = $this->findById($id)['id_colaborador'] ?? null;
            $colaboradorParaGuardar = ($estado === 'asignado') ? $colaboradorPrevio : null;

            $sql = "UPDATE inventario SET
                        nombreEquipo      = ?,
                        marca             = ?,
                        serie             = ?,
                        fechaIngreso      = ?,
                        costo             = ?,
                        estado            = ?,
                        depreciacionMeses = ?,
                        imagen            = ?,
                        thumbnail         = ?,
                        categoria         = ?,
                        comentario        = ?,
                        tipo_equipo       = ?,
                        id_colaborador    = ?
                    WHERE id = ?";
            $params = [
                $nombreEquipo,
                $marca,
                $serie,
                $fechaIngreso,
                $costo,
                $estado,
                $depreciacionMeses,
                $imagen,
                $thumbnail,
                $categoria,
                $estado === 'disponible' ? null : $comentario,
                $tipo_equipo,
                $colaboradorParaGuardar,
                $id
            ];
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            return "Error al actualizar inventario: " . $e->getMessage();
        }
    }

    // Filtramos los equipos por una categoría específica
    public function filtrarPorCategoria($categoria)
    {
        $sql = "SELECT
                    id,
                    nombreEquipo,
                    marca,
                    serie,
                    fechaIngreso,
                    costo,
                    estado,
                    categoria,
                    depreciacionMeses,
                    comentario,
                    imagen,
                    thumbnail,
                    tipo_equipo
                FROM inventario
                WHERE categoria = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$categoria]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mostramos solo los equipos disponibles
    public function listarDisponibles()
    {
        $sql = "SELECT * FROM inventario WHERE estado = 'disponible'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mostramos los equipos disponibles PERO filtrados por tipo
    public function listarDisponiblesPorTipo($tipo)
    {
        $sql = "SELECT * FROM inventario WHERE estado = 'disponible' AND tipo_equipo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Con esto asignamos un equipo a un colaborador, o se lo quitamos 
    public function asignarEquipo($idEquipo, $idColaborador)
    {
        $nuevoEstado = $idColaborador ? 'asignado' : 'disponible';
        $sql = "UPDATE inventario
                SET estado = ?, id_colaborador = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nuevoEstado, $idColaborador, $idEquipo]);
        return true;
    }

    // Obtenemos todos los equipos que le asignamos a un colaborador
    public function obtenerEquiposPorColaborador($idColaborador)
    {
        $sql = "SELECT id, nombreEquipo, marca, serie, fechaIngreso, costo, estado, categoria, tipo_equipo
                FROM inventario
                WHERE id_colaborador = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idColaborador]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Con esta función filtramos los equipos que ya están cerca de la depreciación
    public function filtrarEquiposAlBordeDepreciacion($porcentaje = 0.8)
    {
        $sql = "SELECT *,
                       TIMESTAMPDIFF(MONTH, fechaIngreso, CURDATE()) AS mesesTranscurridos,
                       depreciacionMeses * TIMESTAMPDIFF(MONTH, fechaIngreso, CURDATE()) AS depreciacionAcumulada,
                       costo * ? AS umbral
                FROM inventario
                WHERE depreciacionMeses * TIMESTAMPDIFF(MONTH, fechaIngreso, CURDATE()) >= costo * ?
                ORDER BY depreciacionAcumulada DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$porcentaje, $porcentaje]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>