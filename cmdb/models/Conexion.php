<?php

// Llamamos a los datos de conexión
require_once __DIR__ . '/../config/config.php';

// Con esta clase nos conectamos con la BD
class Conexion {

    // Esta función conecta a la BD usando a config.php
    public static function conectar() {
        try {
            // Aquí construimos el objeto PDO con el host, el nombre de la base de datos, el usuario y la contraseña
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            
            // Configuramos PDO para que nos muestre los errores como excepciones
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Si todo sale bien, devolvemos la conexión
            return $pdo;

        } catch (PDOException $e) {
            // Si hay algún error, lloramos y detenemos todo para mostrar el mensaje XD
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
