<?php
// Iniciar sesión y verificar si el usuario está autenticado
session_start();

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

// Obtener el ID del cliente desde el parámetro GET
$idCliente = intval($_GET['id']);

// Consultar la información del cliente y el precio de la membresía seleccionada
$sql = "SELECT c.id, c.matricula, c.nombre, c.telefono, c.membresia, c.fecha_registro, m.precio as monto_pagado
        FROM clientes c
        INNER JOIN membresia m ON c.membresia = m.nombre
        WHERE c.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idCliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $comprobante = $result->fetch_assoc();
    
    // Crear un comprobante de pago en la tabla "comprobante"
    $insertSQL = "INSERT INTO comprobante (matricula, nombre, telefono, membresia, fecha_registro, monto_pagado, impresiones)
    VALUES (?, ?, ?, ?, ?, ?, 1)
    ON DUPLICATE KEY UPDATE impresiones = impresiones + 1";
$insertStmt = $conn->prepare($insertSQL);
$insertStmt->bind_param("sssssd", $comprobante['matricula'], $comprobante['nombre'], $comprobante['telefono'], $comprobante['membresia'], $comprobante['fecha_registro'], $comprobante['monto_pagado']);
$insertStmt->execute();

    // Mostrar el comprobante con estilo
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Comprobante de Pago</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f9f9f9;
                color: #333;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .comprobante {
                background: #fff;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                width: 100%;
            }
            .comprobante h1 {
                font-size: 24px;
                margin-bottom: 20px;
                text-align: center;
                border-bottom: 2px solid #2164b0;
                padding-bottom: 10px;
            }
            .comprobante p {
                margin: 10px 0;
                font-size: 16px;
            }
            .comprobante button {
                background-color: #2164b0;
                color: #fff;
                border: none;
                border-radius: 5px;
                padding: 10px 20px;
                font-size: 16px;
                cursor: pointer;
                margin-right: 10px;
            }
            .comprobante button:hover {
                background-color: #1a4f8a;
            }
            .comprobante .btn-regresar {
                background-color: #a0a0a0;
                color: #fff;
                text-decoration: none;
            }
            /* Estilo para el botón de regresar */
.btn-regresar {
    background-color: #a0a0a0;
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    display: inline-block;
    cursor: pointer;
}

.btn-regresar:hover {
    background-color: #8a8a8a;
}
            .comprobante .btn-regresar:hover {
                background-color: #8a8a8a;
            }


.btn-regresar:hover {
    background-color: #8a8a8a;
}
        </style>
    </head>
    <body>
        <div class="comprobante">
            <h1>Comprobante de Pago</h1>
            <p><strong>ID:</strong> ' . htmlspecialchars($comprobante['id']) . '</p>
            <p><strong>Matricula:</strong> ' . htmlspecialchars($comprobante['matricula']) . '</p>
            <p><strong>Nombre:</strong> ' . htmlspecialchars($comprobante['nombre']) . '</p>
            <p><strong>Telefono:</strong> ' . htmlspecialchars($comprobante['telefono']) . '</p>
            <p><strong>Membresia:</strong> ' . htmlspecialchars($comprobante['membresia']) . '</p>
            <p><strong>Fecha Registro:</strong> ' . htmlspecialchars($comprobante['fecha_registro']) . '</p>
            <p><strong>Monto Pagado:</strong> $' . number_format($comprobante['monto_pagado'], 2) . '</p>
            <div style="text-align: center;">
                <button onclick="window.print()">Imprimir</button>
                <a href="clientes.php" class="btn-regresar">Regresar</a>
            </div>
        </div>
    </body>
    </html>';

} else {
    echo "No se encontró el cliente.";
}

$stmt->close();
$conn->close();
?>
