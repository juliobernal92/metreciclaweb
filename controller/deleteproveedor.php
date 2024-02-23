<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Eliminar detalle de compra
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["id_proveedor"]) && !empty($_POST["id_proveedor"])) {
        $proveedorId = $_POST["id_proveedor"];

        // Eliminar el detalle de compra
        $stm = $con->prepare("UPDATE proveedores SET activo = FALSE WHERE id_proveedor = :proveedorId;");
        $stm->bindParam(":proveedorId", $proveedorId);

        try {
            $stm->execute();

            // Devolver una respuesta JSON de éxito
            echo json_encode(array("success" => true, "message" => "Proveedor eliminado correctamente"));
            exit();
        } catch (PDOException $e) {
            error_log("Error en la consulta SQL: " . $e->getMessage());

            // Devolver una respuesta JSON con el error
            echo json_encode(array("success" => false, "message" => "Error al eliminar proveedor: " . $e->getMessage()));
            exit();
        }
    }
}