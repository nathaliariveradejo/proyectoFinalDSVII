<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    public function login($correo, $password) {
        $usuario = new Usuario();
        return $usuario->autenticar($correo, $password);
    }
}
