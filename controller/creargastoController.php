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
    $concepto = filter_var($_POST['concepto'], FILTER_SANITIZE_SPECIAL_CHARS);
    $monto = filter_var($_POST['monto'], FILTER_SANITIZE_NUMBER_INT);
    $fecha = filter_var($_POST['fecha'], FILTER_SANITIZE_SPECIAL_CHARS);
    $empleado = filter_var($_POST['idempleado'],FILTER_SANITIZE_NUMBER_INT);



    // Validar nombre solo contiene letras o espacios
    if (!preg_match('/[a-zA-Z ]/', $concepto)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese al menos una letra o espacio en concepto.')));
    }


    // Validar teléfono solo contiene números
    if (!ctype_digit($monto)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese solo números en Monto.')));
    }

    if (!ctype_digit($empleado)) {
        die(json_encode(array('error' => 'Error: Por favor, ingrese solo números en empleados.')));
    }

    try {
        // Si no hay duplicados, proceder con la inserción
        $sql = "INSERT INTO gastos (concepto, monto, fecha, id_empleado) VALUES (:concepto, :monto, :fecha, :empleado)";
        $stmt = $con->prepare($sql);
    
        $stmt->bindParam(':concepto', $concepto, PDO::PARAM_STR);
        $stmt->bindParam(':monto', $monto, PDO::PARAM_INT); 
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindParam(':empleado', $empleado, PDO::PARAM_INT);
    
        $stmt->execute();
        echo json_encode(array('success' => true));
        exit;
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Error al crear proveedor: ' . $e->getMessage()));
    }
    
}
