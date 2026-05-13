<?php
session_start();
// Solo limpiamos los datos de la sala, pero mantenemos la sesión de usuario activa
unset($_SESSION['sala_id']);
unset($_SESSION['sala_nombre']);
// Redirigir a la selección de salas
header("Location: ../salas.php");
exit();
?>
