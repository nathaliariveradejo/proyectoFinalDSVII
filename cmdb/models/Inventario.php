<?php
require_once 'Conexion.php';

class Inventario
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::conectar();
    }

    /**
     * Obtiene todos los ítems de inventario.
     * @return array
     */
    public function listar()
    {
        $sql = "SELECT id, nombreEquipo, marca, serie, fechaIngreso, costo, estado 
                 FROM inventario 
                  ORDER BY id ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un ítem por su ID.
     * @param int $id
     * @return array|false
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM inventario WHERE id = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Inserta un nuevo ítem en inventario.
     * @param string $nombreEquipo
     * @param string $marca
     * @param string $serie
     * @param string $fechaIngreso  formato 'YYYY-MM-DD'
     * @param float  $costo
     * @param int    $depreciacionMeses
     * @param string $imagen        ruta relativa
     * @param string $thumbnail     ruta relativa
     * @return bool|string          true si OK, mensaje de error si falla
     */
    public function guardar($nombreEquipo, $marca, $serie, $fechaIngreso, $costo, $depreciacionMeses, $imagen = null, $thumbnail = null)
    {
        // Validaciones básicas
        if (empty($nombreEquipo) || empty($fechaIngreso)) {
            return "El nombre del equipo y la fecha de ingreso son obligatorios.";
        }
        try {
            $sql = "INSERT INTO inventario 
                (nombreEquipo, marca, serie, fechaIngreso, costo, estado, depreciacionMeses, imagen, thumbnail) 
                VALUES (?, ?, ?, ?, ?, 'disponible', ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $nombreEquipo,
                $marca,
                $serie,
                $fechaIngreso,
                $costo,
                $depreciacionMeses,
                $imagen,
                $thumbnail
            ]);
            return true;
        } catch (PDOException $e) {
            return "Error al guardar inventario: " . $e->getMessage();
        }
    }

    /**
     * Actualiza un ítem existente.
     * @param int    $id
     * @param string $nombreEquipo
     * @param string $marca
     * @param string $serie
     * @param string $fechaIngreso
     * @param float  $costo
     * @param string $estado
     * @param int    $depreciacionMeses
     * @param string $imagen
     * @param string $thumbnail
     * @return bool|string
     */
    public function actualizar($id, $nombreEquipo, $marca, $serie, $fechaIngreso, $costo, $estado, $depreciacionMeses, $imagen = null, $thumbnail = null)
    {
        try {
            $sql = "UPDATE inventario SET 
                        nombreEquipo      = ?, 
                        marca             = ?, 
                        serie             = ?, 
                        fechaIngreso      = ?, 
                        costo             = ?, 
                        estado            = ?, 
                        depreciacionMeses = ?, 
                        imagen            = ?, 
                        thumbnail         = ? 
                    WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $nombreEquipo,
                $marca,
                $serie,
                $fechaIngreso,
                $costo,
                $estado,
                $depreciacionMeses,
                $imagen,
                $thumbnail,
                $id
            ]);
            return true;
        } catch (PDOException $e) {
            return "Error al actualizar inventario: " . $e->getMessage();
        }
    }

    /**
     * Baja lógica (marcar como 'descartado').
     * @param int $id
     * @return bool|string
     */
    public function bajaLogica($id)
    {
        try {
            $sql = "UPDATE inventario SET estado = 'descartado' WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return "Error al dar de baja inventario: " . $e->getMessage();
        }
    }
}
