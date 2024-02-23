<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");
header('Content-Type: text/html; charset=UTF-8');


// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar el token CSRF para evitar ataques CSRF
    if (!isset($_POST['token_csrf']) || $_POST['token_csrf'] !== $_SESSION['token_csrf']) {
        die(json_encode(array('error' => 'Error de seguridad. Intento de CSRF detectado.')));
    }

    // Recoger datos del formulario
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];


    
    // Validar nombre y apellido solo contienen letras
    /*
    if (!ctype_alpha($nombre)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese solo letras en Nombre.')));
    }*/

    if (!preg_match("/^[a-zA-ZñÑ\s']+$/u", $nombre)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese solo letras en Nombre.')));
    }

    // Validar dirección (letras, números, la letra ñÑ y el carácter ')
    if (!preg_match("/^[a-zA-Z0-9ñÑ\s']+$/u", $direccion)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese una dirección válida.')));
    }



    // Validar teléfono solo contiene números
    if (!ctype_digit($telefono)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese solo números en Teléfono.')));
    }

    try {
        // Verificar si el nombre ya esta en uso 
        $consultaNombre = $con->prepare("SELECT COUNT(*) FROM localesventa WHERE nombre = :nombre");
        $consultaNombre->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $consultaNombre->execute();
        $cantidadNombre = $consultaNombre->fetchColumn();

        if ($cantidadNombre > 0) {
            die(json_encode(array('error' => 'Error: El local ya existe.')));
        }
        // Si no hay duplicados, proceder con la inserción
        $sql = "INSERT INTO localesventa (nombre, direccion, telefono) VALUES (:nombre, :direccion, :telefono)";
        $stmt = $con->prepare($sql);

        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);


        $stmt->execute();
        echo json_encode(array('success' => true));
        exit;
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Error al añadir local: ' . $e->getMessage()));
    }
}
