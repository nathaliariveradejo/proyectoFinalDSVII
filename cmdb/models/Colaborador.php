<?php

// Llamamos a la BD para comunicarnos con ella 
require_once 'Conexion.php';

// Creamos esta clase para manejar todo lo relacionado con los colaboradores
class Colaborador
{
    private $pdo;

    // Al construir esta clase, conectamos automáticamente con la base de datos
    public function __construct()
    {
        $this->pdo = Conexion::conectar();
    }

    // Con esta función listamos todos los colaboradores que estén activos
    public function listarActivos()
    {
        $sql = "SELECT id, nombre, apellido, identificacion, foto, ubicacion, telefono, correo 
                FROM colaboradores 
                WHERE estado = 'activo' 
                ORDER BY id ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Aquí buscamos un colaborador según su ID
    public function findById($id)
    {
        $sql = "SELECT * FROM colaboradores WHERE id = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Con esta función guardamos un nuevo colaborador validando que los campos no estén vacíos antes de guardar
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
            // Si la identificación ya existe, capturamos el error
            if ($e->getCode() === '23000') {
                return "La identificación ya está registrada.";
            }
            return "Error al guardar colaborador: " . $e->getMessage();
        }
    }

    // Aquí actualizamos los datos de un colaborador existente
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

    // Esta función la usamos para dar de baja a un colaborador (solo se marca como: inactivo)
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

?>
