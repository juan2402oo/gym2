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

// Obtener el ID de la membresía a editar
$id = intval($_GET['id']);

// Obtener los datos actuales de la membresía
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM membresia WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $membresia = $result->fetch_assoc();
    $stmt->close();
}

// Manejar la actualización de la membresía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $duracion = $_POST['duracion'];
    $precio = $_POST['precio'];

    $sql = "UPDATE membresia SET nombre = ?, duracion = ?, precio = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidi", $nombre, $duracion, $precio, $id);

    if ($stmt->execute()) {
        header("Location: membresia_menu.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Membresía</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilo para el modal */
        .modal {
            display: block; /* Cambiar a 'block' para mostrar el modal */
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
    <!-- Modal para editar membresía -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Editar Membresía</h2>
            <form action="" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($membresia['nombre']); ?>" required>
                <label for="duracion">Duración (días):</label>
                <input type="number" id="duracion" name="duracion" value="<?php echo htmlspecialchars($membresia['duracion']); ?>" required>
                <label for="precio">Precio:</label>
                <input type="text" id="precio" name="precio" value="<?php echo htmlspecialchars($membresia['precio']); ?>" required>
                <button type="submit">Actualizar</button>
            </form>
        </div>
    </div>

    <script>
        function closeModal() {
            window.location.href = "membresia_menu.php"; // Redirige a la página de listado de membresías al cerrar el modal
        }

        // Cerrar el modal si se hace clic fuera del contenido del modal
        window.onclick = function(event) {
            if (event.target == document.getElementById('modal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
