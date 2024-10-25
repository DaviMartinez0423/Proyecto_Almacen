<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

$ventas = json_decode(file_get_contents('http://192.168.100.2:3009/api/ventas'), true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas</title>
</head>
<body>
    <h2>Listado de Ventas</h2>

    <?php if (empty($ventas)): ?>
        <p>No hay ventas registradas.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>ID Auto</th>
                    <th>ID Cliente</th>
                    <th>Color</th>
                    <th>Precio</th>
                    <th>Usado</th>
                    <th>Fecha de Creación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $venta): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($venta['id']); ?></td>
                        <td><?php echo htmlspecialchars($venta['car_id']); ?></td>
                        <td><?php echo htmlspecialchars($venta['customer_id']); ?></td>
                        <td><?php echo htmlspecialchars($venta['color']); ?></td>
                        <td><?php echo htmlspecialchars($venta['price']); ?></td>
                        <td><?php echo $venta['used'] ? 'Sí' : 'No'; ?></td>
                        <td><?php echo htmlspecialchars($venta['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <a href="admin_dashboard.php"><button>Volver al Panel de Administración</button></a>
</body>
</html>
