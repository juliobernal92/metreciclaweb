<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Obtener detalle de compra por ID
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["id_empleado"]) && !empty($_POST["id_empleado"])) {
        $empleadoId = $_POST["id_empleado"];

        // Obtener detalles de compra basado en el ID
        $stm = $con->prepare("
            SELECT id_empleado, nombre, apellido, direccion, telefono, cedula
            FROM empleados
            WHERE id_empleado = :empleadoId;
        ");
        $stm->bindParam(":empleadoId", $empleadoId);
        $stm->execute();
        $empleado = $stm->fetch(PDO::FETCH_ASSOC);

        // Devolver una respuesta JSON con el detalle
        $response = array("success" => true, "empleado" => $empleado);
        echo json_encode($response);
        exit();
    }
}
