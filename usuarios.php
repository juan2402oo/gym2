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

// Manejar la eliminación de usuarios
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM usuarios WHERE id = $id");
    header("Location: usuarios.php");
    exit();
}

// Obtener los usuarios de la base de datos
$result = $conn->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
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

        /* Estilo para el encabezado de la tabla */
        table thead tr {
            background-color: #009879;
            color: white;
            text-align: left;
            font-weight: bold;
        }

        /* Estilo para las filas de la tabla */
        table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        /* Estilo para filas alternas */
        table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        table tbody tr:nth-of-type(odd) {
            background-color: #e9e9e9;
        }

        /* Efecto hover */
        table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        /* Estilo para las celdas */
        table td, table th {
            padding: 12px 15px;
        }

        /* Estilo para los botones de acción */
        table td a {
            display: inline-block;
            padding: 8px 12px;
            margin-right: 5px;
            color: #fff;
            background-color: #009879;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        /* Cambiar color de fondo de los botones al pasar el mouse */
        table td a:hover {
            background-color: #007f5f;
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
        <h1>Usuarios Registrados</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['fecha_registro']; ?></td>
                        <td>
                            <a href="editar_usuarios.php?id=<?php echo $row['id']; ?>">Editar</a>
                            <a href="usuarios.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="scripts.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
