<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Eliminar detalle de compra
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["id_empleado"]) && !empty($_POST["id_empleado"])) {
        $empleadoId = $_POST["id_empleado"];

        // Eliminar el detalle de compra
        $stm = $con->prepare("UPDATE empleados SET activo = FALSE WHERE id_empleado = :empleadoId;");
        $stm->bindParam(":empleadoId", $empleadoId);

        try {
            $stm->execute();

            // Devolver una respuesta JSON de éxito
            echo json_encode(array("success" => true, "message" => "Empleado eliminado correctamente"));
            exit();
        } catch (PDOException $e) {
            error_log("Error en la consulta SQL: " . $e->getMessage());

            // Devolver una respuesta JSON con el error
            echo json_encode(array("success" => false, "message" => "Error al eliminar Empleado: " . $e->getMessage()));
            exit();
        }
    }
}