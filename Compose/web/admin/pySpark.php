<?php
    session_start();
    $user_id = $_SESSION['user_id'];
    $name = $_SESSION['name'];
    $role = $_SESSION['role'];
    if ($role !== "Admin") {
        header("Location: ../index.html");
    }

    $basePath = '/app/output';
    $selected_folder = $_GET['folder'] ?? 'reviews_stats';

    // Mapear las carpetas a los nombres descriptivos
    $folder_map = [
        'reviews_stats' => 'Estadísticas de Reseñas',
        'room_type_distribution' => 'Distribución de Tipos de Habitación',
        'availability_stats' => 'Estadísticas de Disponibilidad',
        'price_stats' => 'Estadísticas de Precios'
    ];

    if (!array_key_exists($selected_folder, $folder_map)) {
        echo "Carpeta no válida seleccionada.";
        exit;
    }

    // Encontrar el primer archivo CSV en la carpeta seleccionada
    $selected_file = glob("$basePath/$selected_folder/*.csv")[0] ?? null;

    if ($selected_file && file_exists($selected_file)) {
        $csvData = file_get_contents($selected_file);
        $rows = array_map("str_getcsv", explode("\n", $csvData));
        $header = array_shift($rows);
    } else {
        echo "No se encontró un archivo CSV en la carpeta seleccionada: " . htmlspecialchars($selected_folder);
        exit;
    }

    // Preparar los datos para Chart.js dependiendo del conjunto de datos seleccionado
    $labels = [];
    $data1 = [];
    $data2 = [];

    foreach ($rows as $row) {
        if ($selected_folder === 'reviews_stats') {
            $labels[] = $row[0] ?? '';  // neighbourhood_group
            $data1[] = (float) ($row[1] ?? 0);  // avg_rating
            $data2[] = (int) ($row[2] ?? 0);    // total_reviews
        } elseif ($selected_folder === 'room_type_distribution') {
            $labels[] = $row[0] ?? '';  // room_type
            $data1[] = (int) ($row[1] ?? 0);    // count
        } elseif ($selected_folder === 'availability_stats') {
            $labels[] = $row[0] ?? '';  // neighbourhood_group
            $data1[] = (float) ($row[1] ?? 0);  // avg_minimum_nights
            $data2[] = (float) ($row[2] ?? 0);  // avg_availability_365
        } elseif ($selected_folder === 'price_stats') {
            $labels[] = $row[1] ?? '';  // room_type
            $data1[] = (float) ($row[2] ?? 0);  // avg_price
            $data2[] = (float) ($row[3] ?? 0);  // max_price
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Dashboard Reservas - PySpark Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <div class="navbar-brand">
                <h1>Airbnb Platform</h1>
            </div>
            <nav class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="admin.php">Inicio</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="container text-center my-3">
        <h2>Selecciona un Conjunto de Datos</h2>
        <!-- Menú desplegable para seleccionar la carpeta de datos -->
        <form method="get" action="">
            <select name="folder" class="form-select" onchange="this.form.submit()">
                <?php foreach ($folder_map as $folder => $description) : ?>
                    <option value="<?php echo htmlspecialchars($folder); ?>" <?= $selected_folder == $folder ? 'selected' : '' ?>>
                        <?php echo htmlspecialchars($description); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- Tabla de datos -->
        <table class="table table-striped table-bordered mt-4">
            <thead>
                <tr>
                    <?php foreach ($header as $col) : ?>
                        <th><?php echo htmlspecialchars($col ?? '', ENT_QUOTES, 'UTF-8'); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row) : ?>
                    <tr>
                        <?php foreach ($row as $cell) : ?>
                            <td><?php echo htmlspecialchars($cell ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Gráficos -->
        <h3>Gráfico de <?php echo htmlspecialchars($folder_map[$selected_folder]); ?></h3>
        <canvas id="chart1" width="300" height="150"></canvas>
        <?php if (!empty($data2)) : ?>
            <canvas id="chart2" width="300" height="150" class="mt-4"></canvas>
        <?php endif; ?>
    </div>

    <script>
        const labels = <?php echo json_encode($labels); ?>;
        const data1 = <?php echo json_encode($data1); ?>;
        const data2 = <?php echo json_encode($data2); ?>;

        // Gráfico principal
        const ctx1 = document.getElementById('chart1').getContext('2d');
        new Chart(ctx1, {
            type: '<?php echo ($selected_folder == "room_type_distribution") ? "pie" : "bar"; ?>',
            data: {
                labels: labels,
                datasets: [{
                    label: '<?php echo $selected_folder === "room_type_distribution" ? "Distribución de Tipos de Habitación" : "Datos Principales"; ?>',
                    data: data1,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Gráfico secundario (solo si hay datos secundarios)
        if (data2.length) {
            const ctx2 = document.getElementById('chart2').getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Datos Secundarios',
                        data: data2,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
