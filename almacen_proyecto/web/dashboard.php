<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

// Obtener datos de los microservicios
$ventas = json_decode(file_get_contents('http://192.168.100.2:3009/api/ventas'), true);
$cars = json_decode(file_get_contents('http://192.168.100.2:3007/api/cars'), true);
$users = json_decode(file_get_contents('http://192.168.100.2:3005/api/usuarios'), true);

// Procesar datos para gráficos

// 1. Top 5 marcas más vendidas
$marcaVentas = [];
foreach ($ventas as $venta) {
    $carId = $venta['car_id'];
    $car = array_values(array_filter($cars, fn($c) => $c['id'] == $carId))[0] ?? null;
    if ($car) {
        $company = $car['company'];
        $marcaVentas[$company] = ($marcaVentas[$company] ?? 0) + 1;
    }
}
arsort($marcaVentas);
$topMarcas = array_slice($marcaVentas, 0, 5, true);

// 2. Porcentaje de carros usados vs nuevos
$usados = array_reduce($ventas, fn($carry, $v) => $carry + $v['used'], 0);
$nuevos = count($ventas) - $usados;

// 3. Ventas por día
$ventasPorFecha = [];
foreach ($ventas as $venta) {
    $fecha = substr($venta['created_at'], 0, 10);
    $ventasPorFecha[$fecha] = ($ventasPorFecha[$fecha] ?? 0) + 1;
}

// 4. Porcentaje de usuarios clientes por género (Male/Female)
$usuariosClientes = array_filter($users, fn($u) => $u['role'] === 'User');
$generoUsuarios = ['Male' => 0, 'Female' => 0];
foreach ($usuariosClientes as $usuario) {
    $generoUsuarios[$usuario['gender']]++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
            margin-top: 50px;
        }
        .chart-container {
            width: 80%;
            max-width: 600px;
        }
        .chart-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .top-right-button {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <button class="top-right-button" onclick="window.location.href='admin_dashboard.php'">Volver a la pagina de Administración</button>
    <h1>Dashboard</h1>

    <div class="dashboard-container">
        <div class="chart-container">
            <div class="chart-title">Top 5 Marcas de Carros Más Vendidas</div>
            <canvas id="topMarcasChart"></canvas>
        </div>

        <div class="chart-container">
            <div class="chart-title">Porcentaje de Carros Usados vs Nuevos</div>
            <canvas id="carrosUsadosChart"></canvas>
        </div>

        <div class="chart-container">
            <div class="chart-title">Ventas Diarias</div>
            <canvas id="ventasPorFechaChart"></canvas>
        </div>

        <div class="chart-container">
            <div class="chart-title">Distribución de Usuarios Clientes por Género</div>
            <canvas id="generoUsuariosChart"></canvas>
        </div>
    </div>

    <script>
        const topMarcasData = {
            labels: <?php echo json_encode(array_keys($topMarcas)); ?>,
            datasets: [{
                label: 'Ventas',
                data: <?php echo json_encode(array_values($topMarcas)); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };
        new Chart(document.getElementById('topMarcasChart'), {
            type: 'bar',
            data: topMarcasData,
            options: { responsive: true }
        });

        const carrosUsadosData = {
            labels: ['Usados', 'Nuevos'],
            datasets: [{
                data: [<?php echo $usados; ?>, <?php echo $nuevos; ?>],
                backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(75, 192, 192, 0.6)']
            }]
        };
        new Chart(document.getElementById('carrosUsadosChart'), {
            type: 'pie',
            data: carrosUsadosData,
            options: { responsive: true }
        });

        const ventasPorFechaData = {
            labels: <?php echo json_encode(array_keys($ventasPorFecha)); ?>,
            datasets: [{
                label: 'Ventas por Día',
                data: <?php echo json_encode(array_values($ventasPorFecha)); ?>,
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        };
        new Chart(document.getElementById('ventasPorFechaChart'), {
            type: 'line',
            data: ventasPorFechaData,
            options: { responsive: true }
        });

        const generoUsuariosData = {
            labels: ['Male', 'Female'],
            datasets: [{
                data: <?php echo json_encode(array_values($generoUsuarios)); ?>,
                backgroundColor: ['rgba(255, 159, 64, 0.6)', 'rgba(255, 205, 86, 0.6)']
            }]
        };
        new Chart(document.getElementById('generoUsuariosChart'), {
            type: 'pie',
            data: generoUsuariosData,
            options: { responsive: true }
        });
    </script>
</body>
</html>