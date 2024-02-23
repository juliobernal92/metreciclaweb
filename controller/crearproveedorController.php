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
    $direccion = filter_var($_POST['direccion'], FILTER_SANITIZE_SPECIAL_CHARS);
    $telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_NUMBER_INT);

    // Validar nombre solo contiene letras o espacios
    if (!preg_match('/[a-zA-Z ]/', $nombre)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese al menos una letra o espacio en Nombre.')));
    }



    // Validar dirección (letras, números y espacios)
    if (!preg_match('/^[a-zA-Z0-9\s]+$/', $direccion)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese letras, números o espacios en Dirección.')));
    }


    // Validar teléfono solo contiene números
    if (!ctype_digit($telefono)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese solo números en Teléfono.')));
    }

    try {
        // Verificar si el teléfono ya está en uso
        $consultaTelefono = $con->prepare("SELECT COUNT(*) FROM proveedores WHERE telefono = :telefono");
        $consultaTelefono->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $consultaTelefono->execute();
        $cantidadTelefono = $consultaTelefono->fetchColumn();

        if ($cantidadTelefono > 0) {
            die(json_encode(array('error' => 'Error: El teléfono ya está en uso.')));
        }

        // Si no hay duplicados, proceder con la inserción
        $sql = "INSERT INTO proveedores (nombre,telefono, direccion) VALUES (:nombre, :telefono, :direccion)";
        $stmt = $con->prepare($sql);

        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);

        $stmt->execute();
        echo json_encode(array('success' => true));
        exit;
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Error al crear proveedor: ' . $e->getMessage()));
    }
}
