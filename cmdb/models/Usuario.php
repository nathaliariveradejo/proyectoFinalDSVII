<?php
require_once 'Conexion.php';

class Usuario
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::conectar();
    }

    public function autenticar($correo, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE correo = ? AND estado = 1 LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && hash('sha256', $password) === $usuario['password']) {
            return $usuario;
        }
        return false;
    }
    public function listarActivos()
    {
        $stmt = $this->pdo->query("SELECT id, nombre, correo FROM usuarios WHERE estado = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, nombre, correo FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar($nombre, $correo)
    {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)");
        $hash = hash('sha256', 'default123'); // por defecto hasta implementar cambio de clave
        $stmt->execute([$nombre, $correo, $hash]);
    }

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

    public function actualizar($id, $nombre, $correo)
    {
        try {
            $sql = "UPDATE usuarios SET nombre = ?, correo = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$nombre, $correo, $id]);
            return true;
        } catch (PDOException $e) {
            return "Error al actualizar: " . $e->getMessage();
        }
    }

    public function registrar($nombre, $correo, $password, $confirmar)
    {
        // Validar campos vacíos
        if (empty($nombre) || empty($correo) || empty($password) || empty($confirmar)) {
            return "Todos los campos son obligatorios.";
        }

        // Validar formato de correo
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return "El formato del correo es inválido.";
        }

        // Confirmar contraseñas
        if ($password !== $confirmar) {
            return "Las contraseñas no coinciden.";
        }

        // Verificar si el correo ya existe
        $sql = "SELECT id FROM usuarios WHERE correo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$correo]);
        if ($stmt->fetch()) {
            return "El correo ya está registrado.";
        }

        // Registrar usuario
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