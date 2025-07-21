<?php

//Llamamos al modelo de Usuario
require_once __DIR__ . '/../models/Usuario.php';

// Creamos esta clase para el proceso de autenticación de los usuarios
class AuthController {

    // Con esta función recibimos el correo y la contraseña, y los enviamos al modelo para verificarlos
    public function login($correo, $password) {
        $usuario = new Usuario();
        return $usuario->autenticar($correo, $password);
    }
}

?>