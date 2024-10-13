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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
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
                    <p>¿Nuevo en el GYM? Crea una cuenta para comenzar hoy mismo.</p>
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
        <div class="carousel">
            <div class="carousel-container">
                <div class="carousel-slide">
                    <img src="image1.jpg" alt="Imagen 1">
                </div>
                <div class="carousel-slide">
                    <img src="image2.jpg" alt="Imagen 2">
                </div>
                <div class="carousel-slide">
                    <img src="image3.jpg" alt="Imagen 3">
                </div>
            </div>
            <button class="carousel-prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="carousel-next" onclick="moveSlide(1)">&#10095;</button>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
