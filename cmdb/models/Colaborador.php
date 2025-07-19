<?php
require_once 'Conexion.php';

class Colaborador
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::conectar();
    }

    /**
     * Devuelve todos los colaboradores activos.
     * @return array
     */
    public function listarActivos()
    {
        $sql = "SELECT id, nombre, apellido, identificacion, ubicacion, telefono, correo 
             FROM colaboradores 
             WHERE estado = 'activo' 
             ORDER BY id ASC";  
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca un colaborador por ID.
     * @param int $id
     * @return array|false
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM colaboradores WHERE id = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Inserta un nuevo colaborador.
     * @param string $nombre
     * @param string $apellido
     * @param string $identificacion
     * @param string $foto
     * @param string $ubicacion
     * @param string $telefono
     * @param string $correo
     * @return bool|string
     */
    public function guardar($nombre, $apellido, $identificacion, $foto = null, $ubicacion = null, $telefono = null, $correo = null)
    {
        if (empty($nombre) || empty($apellido) || empty($identificacion)) {
            return "Nombre, apellido e identificación son obligatorios.";
        }
        try {
            $sql = "INSERT INTO colaboradores 
                    (nombre, apellido, identificacion, foto, ubicacion, telefono, correo, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'activo')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $nombre,
                $apellido,
                $identificacion,
                $foto,
                $ubicacion,
                $telefono,
                $correo
            ]);
            return true;
        } catch (PDOException $e) {
            // 23000: violación de clave única
            if ($e->getCode() === '23000') {
                return "La identificación ya está registrada.";
            }
            return "Error al guardar colaborador: " . $e->getMessage();
        }
    }

    /**
     * Actualiza un colaborador existente.
     * @param int    $id
     * @param string $nombre
     * @param string $apellido
     * @param string $identificacion
     * @param string $foto
     * @param string $ubicacion
     * @param string $telefono
     * @param string $correo
     * @param string $estado          'activo' o 'inactivo'
     * @return bool|string
     */
    public function actualizar($id, $nombre, $apellido, $identificacion, $foto = null, $ubicacion = null, $telefono = null, $correo = null, $estado = 'activo')
    {
        try {
            $sql = "UPDATE colaboradores SET 
                        nombre         = ?, 
                        apellido       = ?, 
                        identificacion = ?, 
                        foto           = ?, 
                        ubicacion      = ?, 
                        telefono       = ?, 
                        correo         = ?, 
                        estado         = ? 
                    WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $nombre,
                $apellido,
                $identificacion,
                $foto,
                $ubicacion,
                $telefono,
                $correo,
                $estado,
                $id
            ]);
            return true;
        } catch (PDOException $e) {
            return "Error al actualizar colaborador: " . $e->getMessage();
        }
    }

    /**
     * Baja lógica (marcar como 'inactivo').
     * @param int $id
     * @return bool|string
     */
    public function bajaLogica($id)
    {
        try {
            $sql = "UPDATE colaboradores SET estado = 'inactivo' WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return "Error al dar de baja colaborador: " . $e->getMessage();
        }
    }
}
