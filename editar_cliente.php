<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    header("Location: inicio.php");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'gym_paq');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del cliente desde la URL
$id = intval($_GET['id']);

// Obtener los datos del cliente
$sql = "SELECT * FROM clientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();

// Obtener los tipos de membresía para el formulario
$membresiaQuery = "SELECT * FROM membresia";
$membresiaResult = $conn->query($membresiaQuery);

// Manejar la actualización del cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matricula = $_POST['matricula'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $estado = $_POST['estado'];
    $inicioMembresia = $_POST['inicioMembresia'];
    $finMembresia = $_POST['finMembresia'];
    $membresia = $_POST['membresia'];

    $sql = "UPDATE clientes SET matricula = ?, nombre = ?, telefono = ?, estado = ?, inicioMembresia = ?, finMembresia = ?, membresia = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $matricula, $nombre, $telefono, $estado, $inicioMembresia, $finMembresia, $membresia, $id);

    if ($stmt->execute()) {
        header("Location: clientes.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilo para el formulario */
        .form-container {
            margin: 50px auto;
            padding: 20px;
            max-width: 600px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            color: #333;
        }

        .form-container input,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            background-color: #009879;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #007f5f;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Editar Cliente</h2>
        <form action="" method="POST">
            <label for="matricula">Matrícula:</label>
            <input type="text" name="matricula" value="<?php echo htmlspecialchars($cliente['matricula']); ?>" required>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>" required>

            <label for="estado">Estado:</label>
            <select name="estado" required>
                <option value="Activo" <?php if ($cliente['estado'] == 'Activo') echo 'selected'; ?>>Activo</option>
                <option value="Vencido" <?php if ($cliente['estado'] == 'Vencido') echo 'selected'; ?>>Vencido</option>
            </select>

            <label for="inicioMembresia">Inicio Membresía:</label>
            <input type="date" name="inicioMembresia" value="<?php echo htmlspecialchars($cliente['inicioMembresia']); ?>" required>

            <label for="finMembresia">Fin Membresía:</label>
            <input type="date" name="finMembresia" value="<?php echo htmlspecialchars($cliente['finMembresia']); ?>" required>

            <label for="membresia">Membresía:</label>
            <select name="membresia" required>
                <?php while ($row = $membresiaResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['nombre']; ?>" <?php if ($cliente['membresia'] == $row['nombre']) echo 'selected'; ?>>
                        <?php echo $row['nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
