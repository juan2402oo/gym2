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

// Función para actualizar el estado de los clientes
function actualizarEstado($conn) {
    $today = date('Y-m-d');
    $sql = "UPDATE clientes SET estado = CASE 
                WHEN CURDATE() BETWEEN inicioMembresia AND finMembresia THEN 'activo'
                ELSE 'vencido'
            END";
    $conn->query($sql);
}

// Llamar a la función para actualizar el estado de todos los clientes
actualizarEstado($conn);

// Manejar la eliminación de clientes
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM clientes WHERE id = $id");
    header("Location: clientes.php");
    exit();
}

// Obtener los clientes de la base de datos
$membresia = $conn->query("SELECT id, nombre FROM membresia");
$result = $conn->query("SELECT * FROM clientes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
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
        table td a:hover {
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
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            position: relative;
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
            max-width: 600px;
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
        .modal form input,
        .modal form select {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .modal form button {
            background-color: #009879;
            color: white;
            border: none;
            padding: 10px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
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
            <li><a href="membresia_menu.php">MEMBRESIA</a></li>
        </ul>
    </div>
    <?php endif; ?>

    <div id="content">
        <h1>Clientes Registrados</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Matrícula</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Inicio Membresía</th>
                    <th>Fin Membresía</th>
                    <th>Membresía</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['matricula']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['telefono']; ?></td>
                        <td><?php echo $row['estado']; ?></td>
                        <td><?php echo $row['inicioMembresia']; ?></td>
                        <td><?php echo $row['finMembresia']; ?></td>
                        <td><?php echo $row['membresia']; ?></td>
                        <td>
                            <a href="editar_cliente.php?id=<?php echo $row['id']; ?>">Editar</a>
                            <a href="clientes.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este cliente?');">Eliminar</a>
                            <a href="exportar_boleta.php?id=<?php echo $row['id']; ?>">Exportar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Botón flotante para agregar un nuevo cliente -->
        <div class="add-client-button" onclick="document.getElementById('addClientModal').style.display='block'">+</div>
    </div>

    <!-- Modal para agregar cliente -->
    <div class="modal" id="addClientModal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Agregar Nuevo Cliente</h2>
            <form action="agregar_cliente.php" method="POST">
                <label for="matricula">Matrícula:</label>
                <input type="text" name="matricula" required>

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>

                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" required>

                <label for="inicioMembresia">Inicio Membresía:</label>
                <input type="date" name="inicioMembresia" required>

                <label for="finMembresia">Fin Membresía:</label>
                <input type="date" name="finMembresia" required>

                <label for="membresia">Membresía:</label>
                <select name="membresia" required>
                    <?php while ($row = $membresia->fetch_assoc()): ?>
                        <option value="<?php echo $row['nombre']; ?>"><?php echo $row['nombre']; ?></option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Agregar Cliente</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('addClientModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('addClientModal').style.display = 'none';
        }

        // Cerrar el modal si se hace clic fuera de él
        window.onclick = function(event) {
            var modal = document.getElementById('addClientModal');
            if (event.target == modal) {
                modal.style.display = "none";
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
