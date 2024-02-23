<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Validar el token CSRF
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $submittedToken = $_POST['token_csrf'];

    if (!hash_equals($_SESSION['token_csrf'], $submittedToken)) {
        die("Error de CSRF: Token no válido.");
    }
}

if (isset($_SESSION['id_empleado'])) {
    $id_empleado = $_SESSION['id_empleado'];

    include('../config/conexion.php');

    try {
        $sql = "SELECT nombre FROM empleados WHERE id_empleado = :id_empleado";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id_empleado', $id_empleado, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $nombreEmpleado = $result['nombre'];

            // Devolver el nombre como JSON
            echo json_encode(array('nombre' => $nombreEmpleado));
        } else {
            echo json_encode(array('error' => 'Empleado no encontrado en la base de datos.'));
        }
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Error al ejecutar la consulta: ' . $e->getMessage()));
    }

    $con = null;
} else {
    echo json_encode(array('error' => 'No se proporcionó un ID de empleado en la sesión.'));
}
?>