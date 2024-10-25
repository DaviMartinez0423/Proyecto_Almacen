<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // ID del usuario actual

// Obtener la lista de autos desde el microservicio de autos
$cars = json_decode(file_get_contents('http://192.168.100.2:3007/api/cars'), true);

// Obtener las ventas del usuario desde el microservicio de ventas
$url = "http://192.168.100.2:3009/api/ventas/customer/{$user_id}";
$ventas = json_decode(file_get_contents($url), true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Panel del Usuario</title>
    <style>
        .top-right-button {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <button class="top-right-button" onclick="window.location.href='index.html'">Volver al Inicio</button>

    <h2>Bienvenido, Usuario</h2>
    <h3>Lista de Autos</h3>
    <ul>
        <?php foreach ($cars as $car): ?>
            <li>ID: <?php echo $car['id']; ?> - 
                <?php echo $car['company'] . ' ' . $car['model']; ?> - 
                Precio Aproximado: $<?php echo number_format($car['aprox_price'], 2); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Comprar Auto</h3>
    <form action="comprar.php" method="POST">
        <label for="car_id">ID del Auto:</label>
        <input type="text" id="car_id" name="car_id" required>
        <br>
        <label for="color">Color:</label>
        <input type="text" id="color" name="color" required>
        <br>
        <label for="used">¿Usado?:</label>
        <input type="checkbox" id="used" name="used">
        <br>
        <button type="submit">Comprar</button>
    </form>

    <h3>Tus Ventas</h3>
    <?php if (empty($ventas)): ?>
        <p>No tienes ventas registradas.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>ID Auto</th>
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
                        <td><?php echo ucfirst(strtolower(htmlspecialchars($venta['color']))); ?></td>
                        <td><?php echo number_format($venta['price'], 2); ?> USD</td>
                        <td><?php echo $venta['used'] ? 'Sí' : 'No'; ?></td>
                        <td><?php echo htmlspecialchars($venta['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
