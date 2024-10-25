<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

$message = ''; // Variable para mostrar el mensaje al final

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $car_id = $_GET['id'];
    $car = file_get_contents("http://192.168.100.2:3007/api/cars/{$car_id}");
    $car_data = json_decode($car, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $car_id = $_POST['id'];
    $company = $_POST['company'];
    $model = $_POST['model'];
    $transmission = $_POST['transmission'];
    $body_style = $_POST['body_style'];
    $aprox_price = $_POST['aprox_price'];

    // Preparar la data para la actualización
    $data = [
        'company' => $company,
        'model' => $model,
        'transmission' => $transmission,
        'body_style' => $body_style,
        'aprox_price' => $aprox_price
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/json",
            'method' => 'PUT',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $url = "http://192.168.100.2:3007/api/cars/{$car_id}";
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response, true);

    // Mostrar mensaje según el resultado
    if ($result && isset($result['message'])) {
        $message = "Auto actualizado exitosamente.";
    } else {
        $message = "Error al actualizar el auto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Auto</title>
</head>
<body>
    <h2>Editar Auto</h2>

    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="admin_dashboard.php">Volver al Panel de Administración</a>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($car_data['id']); ?>">
            <label for="company">Compañía:</label>
            <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($car_data['company']); ?>" required>
            <br>
            <label for="model">Modelo:</label>
            <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($car_data['model']); ?>" required>
            <br>
            <label for="transmission">Transmisión:</label>
            <input type="text" id="transmission" name="transmission" value="<?php echo htmlspecialchars($car_data['transmission']); ?>" required>
            <br>
            <label for="body_style">Estilo de Carrocería:</label>
            <input type="text" id="body_style" name="body_style" value="<?php echo htmlspecialchars($car_data['body_style']); ?>" required>
            <br>
            <label for="aprox_price">Precio Aproximado:</label>
            <input type="text" id="aprox_price" name="aprox_price" value="<?php echo htmlspecialchars($car_data['aprox_price']); ?>" required>
            <br>
            <button type="submit">Actualizar Auto</button>
        </form>
    <?php endif; ?>
</body>
</html>
