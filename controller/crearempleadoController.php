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
    $apellido = filter_var($_POST['apellido'], FILTER_SANITIZE_SPECIAL_CHARS);
    $telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_NUMBER_INT);
    $direccion = filter_var($_POST['direccion'], FILTER_SANITIZE_SPECIAL_CHARS);
    $cedula = filter_var($_POST['cedula'], FILTER_SANITIZE_NUMBER_INT);

    $contrasena = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Usar password_hash para almacenar contraseñas seguras

    // Validar nombre y apellido solo contienen letras
    if (!ctype_alpha($nombre) || !ctype_alpha($apellido)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese solo letras en Nombre y Apellido.')));
    }

    // Validar dirección (letras y números)
    if (!preg_match('/^[a-zA-Z0-9\s]+$/', $direccion)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese letras y números en Dirección.')));
    }

    // Validar contraseña (mínimo 8 caracteres)
    if (strlen($contrasena) < 8) {
        die(json_encode(array('error' => 'Error: La contraseña debe tener al menos 8 caracteres.')));
    }

    // Validar teléfono solo contiene números
    if (!ctype_digit($telefono)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese solo números en Teléfono.')));
    }

    try {
        // Verificar si la cédula ya está en uso
        $consultaCedula = $con->prepare("SELECT COUNT(*) FROM empleados WHERE cedula = :cedula");
        $consultaCedula->bindParam(':cedula', $cedula, PDO::PARAM_INT);
        $consultaCedula->execute();
        $cantidadCedula = $consultaCedula->fetchColumn();

        // Verificar si el teléfono ya está en uso
        $consultaTelefono = $con->prepare("SELECT COUNT(*) FROM empleados WHERE telefono = :telefono");
        $consultaTelefono->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $consultaTelefono->execute();
        $cantidadTelefono = $consultaTelefono->fetchColumn();

        if ($cantidadCedula > 0) {
            die(json_encode(array('error' => 'Error: La cédula ya está en uso.')));
        }

        if ($cantidadTelefono > 0) {
            die(json_encode(array('error' => 'Error: El teléfono ya está en uso.')));
        }

        // Si no hay duplicados, proceder con la inserción
        $sql = "INSERT INTO empleados (nombre, apellido, telefono, direccion, cedula, contraseña) VALUES (:nombre, :apellido, :telefono, :direccion, :cedula, :contrasena)";
        $stmt = $con->prepare($sql);

        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
        $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);

        $stmt->execute();
        echo json_encode(array('success' => true));
        exit;
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Error al crear empleado: ' . $e->getMessage()));
    }
}
