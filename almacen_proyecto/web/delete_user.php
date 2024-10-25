<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $customer_id = $_GET['id'];

    // Eliminar el auto en el microservicio de autos
    $options = [
        'http' => [
            'method'  => 'DELETE',
        ],
    ];

    $context = stream_context_create($options);
    $url = "http://192.168.100.2:3005/api/usuarios/{$customer_id}";
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response, true);

    if ($result && isset($result['message'])) {
        echo "Usuario eliminado exitosamente.";
    } else {
        echo "Error al eliminar el usuario.";
    }
}
?>