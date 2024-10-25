<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

$message = '';  // Variable para mostrar el mensaje al final

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user = file_get_contents("http://192.168.100.2:3005/api/usuarios/{$user_id}");
    $user_data = json_decode($user, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $user_id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $password = $_POST['password']; 

    $data = [
        'name' => $name,
        'role' => $role,
        'phone' => $phone,
        'gender' => $gender,
        'password' => $password,
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json",
            'method'  => 'PUT',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $url = "http://192.168.100.2:3005/api/usuarios/{$user_id}";
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response, true);

    if ($result && isset($result['message'])) {
        $message = "Usuario actualizado exitosamente.";
    } else {
        $message = "Error al actualizar el usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
</head>
<body>
    <h2>Editar Usuario</h2>

    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="admin_dashboard.php">Volver al Panel de Administración</a>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user_data['id']); ?>">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
            <br>
            <label for="role">Rol:</label>
            <select id="role" name="role">
                <option value="Admin" <?php echo $user_data['role'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="User" <?php echo $user_data['role'] === 'User' ? 'selected' : ''; ?>>User</option>
            </select>
            <br>
            <label for="phone">Teléfono:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user_data['phone']); ?>" required>
            <br>
            <label for="gender">Género:</label>
            <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($user_data['gender']); ?>" required>
            <br>
            <label for="password">Contraseña:</label>
            <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($user_data['password']); ?>" required>
            <br>
            <button type="submit">Actualizar Usuario</button>
        </form>
    <?php endif; ?>
</body>
</html>
