<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Eliminar detalle de compra
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["id_detallesventa"]) && !empty($_POST["id_detallesventa"])) {
        $detalleId = $_POST["id_detallesventa"];

        // Eliminar el detalle de compra
        $stm = $con->prepare("DELETE FROM detallesventa WHERE id_detallesventa = :detalleId");
        $stm->bindParam(":detalleId", $detalleId);

        try {
            $stm->execute();

            // Devolver una respuesta JSON de éxito
            echo json_encode(array("success" => true, "message" => "Detalle venta eliminado correctamente"));
            exit();
        } catch (PDOException $e) {
            error_log("Error en la consulta SQL: " . $e->getMessage());

            // Devolver una respuesta JSON con el error
            echo json_encode(array("success" => false, "message" => "Error al eliminar detalle venta: " . $e->getMessage()));
            exit();
        }
    }
}