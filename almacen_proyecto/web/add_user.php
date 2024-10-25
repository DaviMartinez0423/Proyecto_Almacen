<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $name = $_POST['name'];
    $role = $_POST['role'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Validar el rol
    if (!in_array($role, ['Admin', 'User'])) {
        echo "Rol inválido. Debe ser Admin o User.";
        exit();
    }

    // Crear el usuario en el microservicio de usuarios
    $data = [
        'name' => $name,
        'gender' => $gender,
        'phone' => $phone,
        'role' => $role,
        'password' => $password
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $url = 'http://192.168.100.2:3005/api/usuarios';
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response, true);

// Verificar el código de estado HTTP
    $http_response_header = $http_response_header ?? [];
    $status_code = 500; // Código de estado por defecto si no está disponible

    foreach ($http_response_header as $header) {
        if (strpos($header, 'HTTP/') === 0) {
            $status_code = intval(substr($header, 9, 3));
            break;
        }
    }

    if ($status_code === 201) {
        echo "Usuario creado con éxito.";
    } else {
        echo "Error al crear el usuario. Código de estado: " . $status_code;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
</head>
<body>
    <h2>Agregar Usuario</h2>
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
</body>
</html>
