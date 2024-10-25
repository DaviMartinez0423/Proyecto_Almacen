<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company = $_POST['company'];
    $model = $_POST['model'];
    $transmission = $_POST['transmission'];
    $body_style = $_POST['body_style'];
    $aprox_price = $_POST['aprox_price'];

    // Crear el auto en el microservicio de autos
    $data = [
        'company' => $company,
        'model' => $model,
        'transmission' => $transmission,
        'body_style' => $body_style,
        'aprox_price' => $aprox_price
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $url = 'http://192.168.100.2:3007/api/cars';
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response, true);

    if ($result && isset($result['id'])) {
        echo "Auto agregado exitosamente, ID del auto: " . $result['id'];
    } else {
        echo "Error al agregar el auto";
    }
}
?>