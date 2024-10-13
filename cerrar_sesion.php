<?php
session_start(); // Iniciar sesión

// Destruir la sesión
session_unset();
session_destroy();

// Redirigir a la página principal
header("Location: index.php");
exit();
?>
