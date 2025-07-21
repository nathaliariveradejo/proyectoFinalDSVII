<?php

// Llamamos a la conexión con la BD
require_once 'Conexion.php';

// Creamos esta clase para manejar todo lo relacionado con los usuarios
class Usuario
{
    private $pdo;

    // Al construir esta clase, nos conectamos con la BD automáticamente
    public function __construct()
    {
        $this->pdo = Conexion::conectar();
    }

    // Esta función se usa para autenticar a un usuario verificando su correo y contraseña (en hash)
    public function autenticar($correo, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE correo = ? AND estado = 1 LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validamos que la contraseña sea correcta
        if ($usuario && hash('sha256', $password) === $usuario['password']) {
            return $usuario;
        }
        return false;
    }

    // Esta función devuelve todos los usuarios
    public function listarTodos()
    {
        $stmt = $this->pdo->query("SELECT id, nombre, correo, cedula, estado FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscamos un usuario según su ID
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, nombre, correo, cedula, estado FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Con esta función guardamos un nuevo usuario con una contraseña por defecto
    public function guardar($nombre, $correo, $cedula)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, correo, cedula, password, estado) VALUES (?, ?, ?, ?, 1)");
            $hash = hash('sha256', 'default123'); // Contraseña por defecto
            $stmt->execute([$nombre, $correo, $cedula, $hash]);
            return true;
        } catch (PDOException $e) {
            return "Error al guardar usuario: " . $e->getMessage();
        }
    }

    // Esta función se usa para dar de baja a un usuario (se marca como inactivo)
    public function bajaLogica($id)
    {
        try {
            $sql = "UPDATE usuarios SET estado = 'inactivo' WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return "Error al dar de baja usuario: " . $e->getMessage();
        }
    }

    // Aquí actualizamos los datos de un usuario
    public function actualizar($id, $nombre, $correo, $cedula)
    {
        try {
            $sql = "UPDATE usuarios SET nombre = ?, correo = ?, cedula = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nombre, $correo, $cedula, $id]);
            return true;
        } catch (PDOException $e) {
            return "Error al actualizar usuario: " . $e->getMessage();
        }
    }

    // Esta función cambia el estado de un usuario (activo o inactivo)
    public function cambiarEstado($id, $estado)
    {
        try {
            $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$estado, $id]);
            return true;
        } catch (PDOException $e) {
            return "Error al cambiar estado del usuario: " . $e->getMessage();
        }
    }

    // Esta función registra un usuario desde cero, validando los datos
    public function registrar($nombre, $correo, $password, $confirmar)
    {
        // Validamos que los campos no estén vacíos
        if (empty($nombre) || empty($correo) || empty($password) || empty($confirmar)) {
            return "Todos los campos son obligatorios.";
        }

        // Validamos el formato del correo
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return "El formato del correo es inválido.";
        }

        // Verificamos que ambas contraseñas coincidan
        if ($password !== $confirmar) {
            return "Las contraseñas no coinciden.";
        }

        // Revisamos si el correo ya está registrado
        $sql = "SELECT id FROM usuarios WHERE correo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$correo]);
        if ($stmt->fetch()) {
            return "El correo ya está registrado.";
        }

        // Si todo está bien, guardamos el nuevo usuario con su contraseña en hash
        try {
            $hash = hash('sha256', $password);
            $sql = "INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nombre, $correo, $hash]);
            return true;
        } catch (PDOException $e) {
            return "Error al registrar: " . $e->getMessage();
        }
    }
}

?>