<?php
session_start();
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - CMDB</title>
    <link rel="stylesheet" href="css/estilo.css">
    <script>
    function validarFormulario() {
        const correo = document.forms["loginForm"]["correo"].value;
        const pass = document.forms["loginForm"]["password"].value;
        if (correo === "" || pass === "") {
            alert("Por favor, completa todos los campos.");
            return false;
        }
        return true;
    }
    </script>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form name="loginForm" action="index.php" method="post" onsubmit="return validarFormulario()">
            <input type="email" name="correo" placeholder="Correo" required><br>
            <input type="password" name="password" placeholder="Contraseña" required><br>
            <button type="submit" name="login">Ingresar</button>
        </form>
    </div>
</body>
</html>
