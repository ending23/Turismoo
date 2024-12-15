<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

include 'config.php';

// Manejo de acciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $stmt = $pdo->prepare("INSERT INTO lugares (nombre, descripcion) VALUES (?, ?)");
            $stmt->execute([$nombre, $descripcion]);
        } elseif ($_POST['action'] == 'delete' && isset($_POST['id'])) {
            $stmt = $pdo->prepare("DELETE FROM lugares WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        }
    }
}

// Obtener todos los lugares
$stmt = $pdo->prepare("SELECT * FROM lugares");
$stmt->execute();
$lugares = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Lugares</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="admin-container">
        <h1>Administración de Lugares</h1>
        <h2>Agregar un nuevo lugar</h2>
        <form method="post">
            <input type="hidden" name="action" value="add">
            <label for="nombre">Nombre del lugar:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <button type="submit">Agregar</button>
        </form>

        <h2>Lista de Lugares</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lugares as $lugar): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($lugar['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($lugar['descripcion']); ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $lugar['id']; ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn">Volver al inicio</a>
    </div>
</body>
</html>
