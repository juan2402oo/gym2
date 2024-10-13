<?php
session_start();

// Verificar si el usuario está autenticado
$loggedIn = isset($_SESSION['id']);

if (!$loggedIn) {
    header("Location: inicio.php");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'gym_paq');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener ID del cliente
$cliente_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener asistencias del cliente
$stmt = $conn->prepare("SELECT fecha, hora FROM asistencias WHERE cliente_id = ?");
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$asistencias_result = $stmt->get_result();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias del Cliente</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        h1 {
            font-size: 50px;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }
        table thead tr {
            background-color: #009879;
            color: white;
            text-align: left;
            font-weight: bold;
        }
        table tbody tr {
            border-bottom: 1px solid #dddddd;
        }
        table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }
        table tbody tr:nth-of-type(odd) {
            background-color: #e9e9e9;
        }
        table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
        table td, table th {
            padding: 12px 15px;
        }
        .print-button, .back-button {
            background-color: #009879;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 20px 10px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
        .print-button:hover, .back-button:hover {
            background-color: #007f5f;
        }
        .button-container {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="banner">
        <div class="banner-content">
            <span class="menu-icon" onclick="toggleSidebar()">&#9776;</span>
            <a href="index.php"><img src="logo.png" alt="Logo" class="logo"></a>
        </div>
        <div class="login-container">
            <a href="index.php?logout=true" class="logout-button">Cerrar sesión</a>
        </div>
    </div>

    <div class="sub-banner" id="subBanner">
        <div class="sub-banner-item"><a href="accesorios.php">Accesorios</a></div>
        <div class="sub-banner-item"><a href="maquinas.php">Máquinas</a></div>
        <div class="sub-banner-item"><a href="suplementos.php">Suplementos</a></div>
        <div class="sub-banner-item"><a href="membresia.php">Membresía</a></div>
    </div>

    <?php if ($loggedIn): ?>
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="index.php">INICIO</a></li>
            <li><a href="usuarios.php">USUARIOS</a></li>
            <li><a href="clientes.php">CLIENTES</a></li>
            <li><a href="asistencias.php">ASISTENCIAS</a></li>
            <li><a href="membresia_menu.php">MEMBRESIA</a></li>
        </ul>
    </div>
    <?php endif; ?>

    <div id="content">
        <h1>Asistencias del Cliente</h1>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $asistencias_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['fecha']; ?></td>
                        <td><?php echo $row['hora']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="button-container">
            <button class="print-button" onclick="window.print()">Imprimir Asistencias</button>
            <a href="asistencias.php" class="back-button">Volver a Asistencias</a>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            if (sidebar.style.display === 'none' || sidebar.style.display === '') {
                sidebar.style.display = 'block';
            } else {
                sidebar.style.display = 'none';
            }
        }
    </script>
        <script src="scripts.js"></script>
</body>
</html>
