<?php
abstract class ControllerBase
{
    protected function view(string $path, array $data = [])
    {
        extract($data);
        require __DIR__ . '/../public/' . $path . '.php';
    }

    protected function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }
}
