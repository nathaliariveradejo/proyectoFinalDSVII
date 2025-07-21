<?php
session_start();
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CMDB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a3a8f 0%, #2c5aa0 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, #4a7bff 0%, #1a3a8f 100%);
        }
        
        h2 {
            color: #1a3a8f;
            margin-bottom: 30px;
            font-size: 2rem;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        
        h2 i {
            background: #4a7bff;
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .input-group {
            position: relative;
        }
        
        input {
            width: 100%;
            padding: 15px 20px 15px 50px;
            font-size: 1rem;
            border: 1px solid #d1d9e6;
            border-radius: 8px;
            background: #f8f9fc;
            color: #2c3e50;
            transition: all 0.3s ease;
        }
        
        input:focus {
            outline: none;
            border-color: #4a7bff;
            box-shadow: 0 0 0 3px rgba(74, 123, 255, 0.2);
            background: white;
        }
        
        .input-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #4a7bff;
            font-size: 1.1rem;
        }
        
        button[type="submit"] {
            background: linear-gradient(90deg, #1a3a8f 0%, #2c5aa0 100%);
            color: white;
            border: none;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(26, 58, 143, 0.3);
        }
        
        button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(26, 58, 143, 0.4);
        }
        
        .error {
            background: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
            border-left: 4px solid #c62828;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .error i {
            font-size: 1.2rem;
        }
        
        .footer-links {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #64748b;
        }
        
        .footer-links a {
            color: #4a7bff;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 0 20px;
            }
            
            h2 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2><i class="fas fa-lock"></i> Iniciar Sesión</h2>
        
        <?php if ($error): ?>
            <div class="error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        <?php endif; ?>
        
        <form name="loginForm" action="index.php" method="post" onsubmit="return validarFormulario()">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="correo" placeholder="Correo electrónico" required>
            </div>
            
            <div class="input-group">
                <i class="fas fa-key"></i>
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            
            <button type="submit" name="login">
                <i class="fas fa-sign-in-alt"></i> Ingresar
            </button>
        </form>
    </div>

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
</body>
</html>