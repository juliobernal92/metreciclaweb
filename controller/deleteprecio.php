<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Eliminar detalle de compra
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["id_precioventa"]) && !empty($_POST["id_precioventa"])) {
        $precioId = $_POST["id_precioventa"];

        // Eliminar el detalle de compra
        $stm = $con->prepare("UPDATE precioventa SET activo = FALSE WHERE id_precioventa = :precioId;");
        $stm->bindParam(":precioId", $precioId);

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