<?php

// Este archivo es para evitar el ingreso a otras vistas sin estar logueado 
session_start();

if (!isset($_SESSION['usuario'])) {
    // Si no hay sesión activa, redirigimos al login
    header("Location: login.php");
    exit;
}

// Si el usuario está logueado, lo guardamos en una variable
$usuario = $_SESSION['usuario'];

?>