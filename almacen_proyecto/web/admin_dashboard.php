<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

// Obtener la lista de autos y usuarios
$cars = file_get_contents('http://192.168.100.2:3007/api/cars');
$cars = json_decode($cars, true);

$users = file_get_contents('http://192.168.100.2:3005/api/usuarios');
$users = json_decode($users, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        .top-right-button {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .bottom-center-button {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>
    <button class="top-right-button" onclick="window.location.href='index.html'">Volver al Inicio</button>
    
    <h2>Bienvenido, Administrador</h2>
    <br>
    <!-- Gestión de Autos -->
    <h3>Gestionar Autos</h3>
    <ul>
        <?php foreach ($cars as $car): ?>
            <li>
                ID: <?php echo htmlspecialchars($car['id']); ?> - 
                Compañía: <?php echo htmlspecialchars($car['company']); ?> - 
                Modelo: <?php echo htmlspecialchars($car['model']); ?> - 
                Precio Aproximado: <?php echo htmlspecialchars($car['aprox_price']); ?> 
                <a href="edit_car.php?id=<?php echo htmlspecialchars($car['id']); ?>">Editar</a> | 
                <a href="delete_car.php?id=<?php echo htmlspecialchars($car['id']); ?>">Eliminar</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Agregar Auto</h3>
    <form action="add_car.php" method="POST">
        <label for="company">Compañía:</label>
        <input type="text" id="company" name="company" required>
        <br>
        <label for="model">Modelo:</label>
        <input type="text" id="model" name="model" required>
        <br>
        <label for="transmission">Transmision:</label>
        <input type="text" id="transmission" name="transmission" required>
        <br>
        <label for="body_style">Estilo de Carrocería:</label>
        <input type="text" id="body_style" name="body_style" required>
        <br>
        <label for="aprox_price">Precio Aproximado:</label>
        <input type="text" id="aprox_price" name="aprox_price" required>
        <br>
        <button type="submit">Agregar Auto</button>
    </form>

    <!-- Gestión de Usuarios -->
    <h3>Gestionar Usuarios</h3>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                ID: <?php echo htmlspecialchars($user['id']); ?> - 
                Nombre: <?php echo htmlspecialchars($user['name']); ?> - 
                Rol: <?php echo htmlspecialchars($user['role']); ?>
                <a href="edit_user.php?id=<?php echo htmlspecialchars($user['id']); ?>">Editar</a> |
                <a href="delete_user.php?id=<?php echo htmlspecialchars($user['id']); ?>">Eliminar</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Agregar Usuario</h3>
    <form action="add_user.php" method="POST">
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="role">Rol:</label>
        <select id="role" name="role" required>
            <option value="Admin">Admin</option>
            <option value="User">User</option>
        </select>
        <br>
        <label for="gender">Género:</label>
        <input type="text" id="gender" name="gender" required>
        <br>
        <label for="phone">Teléfono:</label>
        <input type="text" id="phone" name="phone" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Agregar Usuario</button>
    </form>

    <br><br>
    <form action="get_ventas.php" method="GET">
        <button type="submit">Ver Ventas</button>
    </form>


    <button class="bottom-center-button" onclick="window.location.href='dashboard.php'">Ir a Dashboard</button>
</body>
</html>
