<?php
session_start();
include 'config.php';

$error = '';
$success = '';

// Procesar inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Credenciales inválidas';
    }
}

// Procesar registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['new_username'];
    $password = $_POST['new_password'];
    $role = $_POST['role'];

    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $error = 'El usuario ya existe';
    } else {
        // Cifrar la contraseña y guardar el usuario
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, role) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $hashedPassword, $role])) {
            $success = 'Usuario registrado exitosamente';
        } else {
            $error = 'Error al registrar el usuario';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="login-register-container">
    <div class="form-container">
        <h1>Iniciar Sesión</h1>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <form method="post">
            <input type="hidden" name="login">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>

    <div class="form-container">
        <h1>Registrar Usuario</h1>
        <form method="post">
            <input type="hidden" name="register">
            <label for="new_username">Usuario:</label>
            <input type="text" id="new_username" name="new_username" required>
            <label for="new_password">Contraseña:</label>
            <input type="password" id="new_password" name="new_password" required>
            <label for="role">Rol:</label>
            <select id="role" name="role" required>
                <option value="cliente">Cliente</option>
                <option value="admin">Administrador</option>
            </select>
            <button type="submit">Registrar</button>
        </form>
    </div>
</div>
</body>
</html>
