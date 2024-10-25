<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener las ventas del cliente actual
$ventas = file_get_contents("http://192.168.100.2:3009/api/ventas/customer/{$_SESSION['user_id']}");
$ventas_data = json_decode($ventas, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Compras</title>
</head>
<body>
    <h2>Mis Compras</h2>
    <ul>
        <?php foreach ($ventas_data as $venta): ?>
            <li><?php echo 'Carro ID: ' . $venta['car_id'] . ', Precio: ' . $venta['price']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>