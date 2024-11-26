<?php
session_start();

function cargarUsuarios()
{
    $usuarios = file_exists('./json/users.json') ? json_decode(file_get_contents('./json/users.json'), true) : [];
    return $usuarios;
}

function guardarUsuarios($usuarios)
{
    file_put_contents('./json/users.json', json_encode($usuarios, JSON_PRETTY_PRINT));
}

function verificarCredenciales($username, $password)
{
    $usuarios = cargarUsuarios();
    foreach ($usuarios as $usuario) {
        if ($usuario['username'] === $username && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
    }
    return null;
}

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($password !== $confirmPassword) {
            $mensaje = "Las contraseñas no coinciden.";
        } else {
            $usuarios = cargarUsuarios();
            foreach ($usuarios as $usuario) {
                if ($usuario['username'] === $username) {
                    $mensaje = "El nombre de usuario ya está registrado.";
                    break;
                }
            }

            if (!$mensaje) {
                $usuarios[] = [
                    'id' => uniqid(),
                    'username' => $username,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ];
                guardarUsuarios($usuarios);
                $mensaje = "¡Registro exitoso! Ahora puedes iniciar sesión.";
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
        $username = htmlspecialchars($_POST['username']);
        $password = $_POST['password'];
        $usuario = verificarCredenciales($username, $password);

        if ($usuario) {
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['userId'] = $usuario['id'];
        } else {
            $mensaje = "Usuario o contraseña incorrectos.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'logout') {
        session_destroy();
        header("Location: index.php");
        exit;
    }
}

// Redirección al dashboard si está logueado
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Registro y Login</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div id="app" class="animateanimated animatefadeIn">
        <h1 class="animateanimated animateslideInDown">Sistema de Login</h1>
        <p class="indexMessage"><?= $mensaje ?></p>
        <div id="forms">
            <form action="index.php" method="POST" class="fade-in"> <input type="hidden" name="action" value="register">
                <h2>Registro</h2>
                <input type="text" name="username" placeholder="Nombre de Usuario" required> <input type="email"
                    name="email" placeholder="Correo Electrónico" required> <input type="password" name="password"
                    placeholder="Contraseña" required> <input type="password" name="confirmPassword"
                    placeholder="Confirmar Contraseña" required> <button type="submit">Registrarse</button>
            </form>
            <form action="index.php" method="POST" class="fade-in"> <input type="hidden" name="action" value="login">
                <h2>Iniciar Sesión</h2>
                <input type="text" name="username" placeholder="Nombre de Usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</body>
<script src="script.js"></script>

</html>