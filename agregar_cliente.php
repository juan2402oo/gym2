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

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula = $_POST['matricula'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $inicioMembresia = $_POST['inicioMembresia'];
    $finMembresia = $_POST['finMembresia'];
    $membresia = $_POST['membresia'];

    // Determinar el estado en función de las fechas de la membresía
    $hoy = date('Y-m-d');
    $estado = ($hoy >= $inicioMembresia && $hoy <= $finMembresia) ? 'Activo' : 'Vencido';

    $stmt = $conn->prepare("INSERT INTO clientes (matricula, nombre, telefono, estado, inicioMembresia, finMembresia, membresia) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $matricula, $nombre, $telefono, $estado, $inicioMembresia, $finMembresia, $membresia);
    
    if ($stmt->execute()) {
        header("Location: clientes.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cliente</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Agregar Nuevo Cliente</h1>
    <form action="agregar_cliente.php" method="POST">
        <label for="matricula">Matrícula:</label>
        <input type="text" name="matricula" required><br>
        
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" required><br>

        <label for="inicioMembresia">Inicio Membresía:</label>
        <input type="date" name="inicioMembresia" required><br>

        <label for="finMembresia">Fin Membresía:</label>
        <input type="date" name="finMembresia" required><br>

        <label for="membresia">Membresía:</label>
        <input type="text" name="membresia" required><br>

        <button type="submit">Agregar Cliente</button>
    </form>
</body>
</html>
