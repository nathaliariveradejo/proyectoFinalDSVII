<?php
require_once 'Conexion.php';

abstract class ModelBase
{
    protected $pdo;
    protected $errors = [];

    public function __construct()
    {
        $this->pdo = Conexion::conectar();
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
