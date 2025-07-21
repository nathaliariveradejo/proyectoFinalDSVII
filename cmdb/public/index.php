<?php

// Este archivo actúa como punto de entrada del sistema cuando se envía el formulario de login
// Si las credenciales son correctas, inicia sesión y redirige al dashboard
// Si no, redirige nuevamente al login con un mensaje de error
session_start();
require_once '../controllers/AuthController.php';

if (isset($_POST['login'])) {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $auth = new AuthController();
    $usuario = $auth->login($correo, $password);

    if ($usuario) {
        $_SESSION['usuario'] = $usuario;
        header("Location: dashboard.php");
    } else {
        $_SESSION['error'] = "Credenciales incorrectas.";
        header("Location: login.php");
    }
} else {
    header("Location: login.php");
}

?>