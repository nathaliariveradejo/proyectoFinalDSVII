<?php

// Creamos esta clase abstracta para que sea nuestra base de controladores 
// Es decir, que reutilizaremos sus funciones en ellos
abstract class ControllerBase
{
    // Con esta función 'view' cargamos una vista desde el paquete public
    protected function view(string $path, array $data = [])
    {
        // Esto nos permite convertir un arreglo en variables para utilizarlas dentro de la vista
        extract($data);
        require __DIR__ . '/../public/' . $path . '.php';
    }

    // Esto lo utilizamos para redirigir a otra página utilizando su header donde se ubica
    protected function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }
}

?>
