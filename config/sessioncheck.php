<?php
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION['id_empleado'])) {
    // La sesión no está iniciada, redirigir a login.php
    header('Location: login.php');
    exit();
}

$id_empleado = $_SESSION['id_empleado'];
