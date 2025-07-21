<?php

// Llamamos a la conexión a la BD
require_once 'Conexion.php';

// Creamos esta clase abstracta para que sirva de base para todos los modelos
abstract class ModelBase
{
    protected $pdo;
    protected $errors = [];

    // Al construir esta clase, nos conectamos con la BD automáticamente
    public function __construct()
    {
        $this->pdo = Conexion::conectar();
    }

    // Esta función nos devuelve los errores ocurridos
    public function getErrors(): array
    {
        return $this->errors;
    }

    // Esta función nos dice si hay errores o no
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}

?>