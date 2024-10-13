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

// Manejar la asistencia
$asistenciaRegistrada = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $fecha_asistencia = date('Y-m-d');
    $hora_asistencia = date('H:i:s');

    // Insertar la asistencia en la tabla
    $stmt = $conn->prepare("INSERT INTO asistencias (cliente_id, fecha, hora) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $cliente_id, $fecha_asistencia, $hora_asistencia);
    
    if ($stmt->execute()) {
        $asistenciaRegistrada = true;
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Obtener los clientes registrados
$result = $conn->query("SELECT id, nombre, membresia FROM clientes");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Asistencias</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilo para la tabla */
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
        table td form {
            margin: 0;
        }
        table td form button, table td a {
            background-color: #009879;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
        table td form button:hover, table td a:hover {
            background-color: #007f5f;
        }
        /* Estilo para el botón flotante */
        .add-client-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #009879;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 36px;
            text-align: center;
            line-height: 60px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        .add-client-button:hover {
            background-color: #007f5f;
        }
        /* Estilo para el mensaje flotante */
        .toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            position: fixed;
            z-index: 1;
            left: 50%;
            bottom: 30px;
            font-size: 17px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.5s, visibility 0.5s;
        }
        .toast.show {
            visibility: visible;
            opacity: 1;
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
        <h1>Control de Asistencias</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Membresía</th>
                    <th>Marcar Asistencia</th>
                    <th>Ver Asistencias</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['membresia']; ?></td>
                        <td>
                            <form action="asistencias.php" method="POST">
                                <input type="hidden" name="cliente_id" value="<?php echo $row['id']; ?>">
                                <button type="submit">Marcar Asistencia</button>
                            </form>
                        </td>
                        <td>
                            <a href="ver_asistencias.php?id=<?php echo $row['id']; ?>" class="view-attendance-button">Ver Asistencias</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">Asistencia marcada exitosamente!</div>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            if (sidebar.style.display === 'none' || sidebar.style.display === '') {
                sidebar.style.display = 'block';
            } else {
                sidebar.style.display = 'none';
            }
        }

        // Mostrar el mensaje flotante si la asistencia fue registrada
        <?php if ($asistenciaRegistrada): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('Asistencia marcada exitosamente!');
        });
        <?php endif; ?>

        function showToast(message) {
            var toast = document.getElementById("toast");
            toast.textContent = message;
            toast.className = "toast show";
            setTimeout(function() {
                toast.className = toast.className.replace("show", "");
            }, 3000); // Mostrar por 3 segundos
        }
    </script>
            <script src="scripts.js"></script>
</body>
</html>
