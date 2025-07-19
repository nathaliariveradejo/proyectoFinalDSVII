<?php
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
