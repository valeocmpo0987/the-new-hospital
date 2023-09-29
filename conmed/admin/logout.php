<?php
// Inicia la sesión (asegúrate de haber llamado a session_start() en tu página de inicio de sesión)
session_start();

// Destruye la sesión
session_destroy();

// Redirige al usuario al inicio de sesión
header("Location: ../login.php");
exit(); // Asegura que el script se detenga después de la redirección
?>
