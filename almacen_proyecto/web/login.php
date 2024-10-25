<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $password = $_POST['password'];

    // Llamar al microservicio de autenticación de usuarios
    $url = "http://192.168.100.2:3005/api/login/{$id}/{$password}";
    $response = file_get_contents($url);
    
    // Decodificar el JSON recibido
    $user = json_decode($response, true);

    // Verificar si la respuesta contiene los datos correctos
    if ($user && isset($user['id'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirigir según el rol del usuario
        if ($user['role'] === 'Admin') {
            header('Location: admin_dashboard.php');
        } else if ($user['role'] === 'User') {
            header('Location: usuario.php');
        } else {
            echo "Rol no reconocido.";
        }
        exit();
    } else {
        $error = "Credenciales incorrectas o error en el microservicio.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST">
        <label for="id">Id:</label>
        <input type="id" id="id" name="id" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Iniciar Sesión</button>
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
