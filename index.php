<?php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Bienvenido, <?php echo htmlspecialchars($username); ?> </h1>
        <?php if ($role === 'admin'): ?>
            <h2>Administración del grupo</h2>
            <a href="admin.php" class="btn">Administrar Lugares</a>
        <?php else: ?>
            <h2>Vista del grupo</h2>
            <p>Aquí puedes visualizar información sobre el grupo relacionado con Las Coloradas.</p>
        <?php endif; ?>
        <a href="logout.php" class="btn">Cerrar sesión</a>
    </div>
</body>
</html>
