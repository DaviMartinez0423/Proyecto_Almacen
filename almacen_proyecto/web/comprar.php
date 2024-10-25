<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = $_POST['car_id'];
    $color = $_POST['color'];
    $used = isset($_POST['used']) ? true : false;

    // Crear la venta en el microservicio de ventas
    $data = [
        'car_id' => $car_id,
        'customer_id' => $_SESSION['user_id'], // El ID del usuario actual
        'color' => $color,
        'used' => $used
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $url = 'http://192.168.100.2:3009/api/ventas';  // Microservicio de ventas
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response, true);

    if ($result && isset($result['id'])) {
        echo "Compra realizada con éxito, ID de la venta: " . $result['id'];
    } else {
        echo "Error al realizar la compra";
    }
}
?>