<?php
session_start();

// Verificar si el usuario está autenticado
$loggedIn = isset($_SESSION['id']);

// Manejar el cierre de sesión
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirige a la página de inicio
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suplementos</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos generales para el grid de productos */
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        h1 {
            margin-top: 60px;
        }

        /* Estilos para cada item del producto */
        .product-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            padding: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-item img {
            max-width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .product-item h3 {
            font-size: 1.25rem;
            margin: 10px 0;
        }

        .product-item p {
            font-size: 1rem;
            color: #555;
        }

        /* Efectos al pasar el ratón sobre el item del producto */
        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .product-item:hover img {
            transform: scale(1.05);
            transition: transform 0.3s;
        }

        /* Estilo del banner de productos */
        #content img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Agrega un poco de espacio alrededor del contenedor de productos */
        #content {
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 8px;
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
            <?php if ($loggedIn): ?>
                <a href="index.php?logout=true" class="logout-button">Cerrar sesión</a>
            <?php else: ?>
                <button class="login-button" onclick="toggleLoginMenu()">Iniciar sesión &#9662;</button>
                <div class="login-menu" id="loginMenu">
                    <p>Usuarios registrados</p>
                    <p>¿Tienes una cuenta? Inicia sesión ahora.</p>
                    <a href="inicio.php">Iniciar sesión</a>
                    <hr>
                    <p>Clientes nuevos</p>
                    <p>¿Nuevo en GoDaddy? Crea una cuenta para comenzar hoy mismo.</p>
                    <a href="registro.php">Crear una cuenta</a>
                </div>
            <?php endif; ?>
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
        <h1>Suplementos</h1>
        <div class="product-grid">
            <div class="product-item">
                <img src="suplemento1.png" alt="Suplemento 1">
                <h3>Suplementos Gym Dymatize Mass Gainer</h3>
                <p>Su principal función es la de brindarte los nutrientes necesarios para ganar peso muscular.</p>
            </div>
            <div class="product-item">
                <img src="suplemento2.png" alt="Suplemento 2">
                <h3>Pre-Entrenamiento</h3>
                <p>Enfocados a brindarte esa potencia que necesitas antes de cada entrenamiento en el gimnasio.</p>
            </div>
            <div class="product-item">
                <img src="suplemento3.png" alt="Suplemento 3">
                <h3>Quemadores De Grasa y L-Carnitina</h3>
                <p>Suplementos deportivos dirigidos a realizar la función de eliminar el exceso de grasa muscular.</p>
            </div>
            <div class="product-item">
                <img src="suplemento4.png" alt="Suplemento 4">
                <h3>Vitaminas y Minerales</h3>
                <p>Vitaminas para ganar masa muscular.</p>
            </div>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
