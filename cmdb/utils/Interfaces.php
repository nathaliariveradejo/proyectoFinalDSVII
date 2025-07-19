<?php
interface ErrorHandlerInterface
{
    public function getErrors(): array;    // devuelve lista de errores
    public function hasErrors(): bool;     // si hubo fallo
}