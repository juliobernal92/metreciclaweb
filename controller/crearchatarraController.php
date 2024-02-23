<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar el token CSRF para evitar ataques CSRF
    if (!isset($_POST['token_csrf']) || $_POST['token_csrf'] !== $_SESSION['token_csrf']) {
        die(json_encode(array('error' => 'Error de seguridad. Intento de CSRF detectado.')));
    }

    // Recoger datos del formulario
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_SPECIAL_CHARS);
    $precio = filter_var($_POST['precio'], FILTER_SANITIZE_NUMBER_INT);

    // Validar nombre solo contiene letras o espacios
    if (!preg_match('/[a-zA-Z ]/', $nombre)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese al menos una letra o espacio en Nombre.')));
    }


    // Validar teléfono solo contiene números
    if (!ctype_digit($precio)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese solo números en Precio.')));
    }

    try {

        // Si no hay duplicados, proceder con la inserción
        $sql = "INSERT INTO chatarra (nombre,precio) VALUES (:nombre, :precio)";
        $stmt = $con->prepare($sql);

        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);

        $stmt->execute();
        echo json_encode(array('success' => true));
        exit;
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Error al crear proveedor: ' . $e->getMessage()));
    }
}
