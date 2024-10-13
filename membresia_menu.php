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

// Manejar la eliminación de membresías
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM membresia WHERE id = $id");
    header("Location: membresia_menu.php");
    exit();
}

// Manejar la adición de nuevas membresías
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $duracion = $_POST['duracion'];
    $precio = $_POST['precio'];

    $sql = "INSERT INTO membresia (nombre, duracion, precio) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sid", $nombre, $duracion, $precio);

    if ($stmt->execute()) {
        header("Location: membresia_menu.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Obtener las membresías de la base de datos
$result = $conn->query("SELECT * FROM membresia");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membresías</title>
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

        /* Estilo para el botón flotante */
        .floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background-color: #009879;
            color: white;
            border-radius: 50%;
            text-align: center;
            font-size: 30px;
            line-height: 50px;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }

        .floating-button:hover {
            background-color: #007f5f;
        }

        /* Estilo para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal form {
            display: flex;
            flex-direction: column;
        }

        .modal form label {
            margin: 10px 0 5px;
        }

        .modal form input {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
        }

        .modal form button {
            background-color: #009879;
            color: white;
            border: none;
            padding: 10px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
        }

        .modal form button:hover {
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
            <li><a href="membresia_menu.php">MEMBRESÍA</a></li>
        </ul>
    </div>
    <?php endif; ?>

    <div id="content">
        <h1>Membresías Registradas</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Duración</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['duracion']; ?> días</td>
                        <td>$<?php echo number_format($row['precio'], 2); ?></td>
                        <td>
                            <a href="editar_membresia.php?id=<?php echo $row['id']; ?>">Editar</a>
                            <a href="membresia_menu.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar esta membresía?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Botón flotante -->
    <div class="floating-button" onclick="openModal()">+</div>

    <!-- Modal para agregar membresía -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Agregar Membresía</h2>
            <form action="" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="duracion">Duración (días):</label>
                <input type="number" id="duracion" name="duracion" required>
                <label for="precio">Precio:</label>
                <input type="text" id="precio" name="precio" required>
                <button type="submit">Agregar</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        // Cerrar el modal si se hace clic fuera del contenido del modal
        window.onclick = function(event) {
            if (event.target == document.getElementById('modal')) {
                closeModal();
            }
        }
    </script>
        <script src="scripts.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>