<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Editar detalle de compra (solo cantidad)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (
        isset($_POST["id_proveedor"]) && !empty($_POST["id_proveedor"]) &&
        isset($_POST["nuevoNombre"]) && !empty($_POST["nuevoNombre"] &&
        isset($_POST["nuevaDireccion"]) &&!empty($_POST["nuevaDireccion"]) &&
        isset($_POST["nuevoTelefono"]) &&!empty($_POST["nuevoTelefono"])        
        )
    ) {
        $proveedorId = $_POST["id_proveedor"];
        $nuevoNombre = $_POST["nuevoNombre"];
        $nuevaDireccion = $_POST["nuevaDireccion"];
        $nuevoTelefono = $_POST["nuevoTelefono"];

        try {
            // Actualizar la cantidad y recalcular el subtotal
            $stm = $con->prepare("
                UPDATE proveedores
                SET nombre = :nuevoNombre, direccion = :nuevaDireccion, telefono = :nuevoTelefono 
                WHERE id_proveedor = :proveedorId
            ");
            $stm->bindParam(":nuevoNombre", $nuevoNombre);
            $stm->bindParam(":nuevaDireccion", $nuevaDireccion);
            $stm->bindParam(":nuevoTelefono", $nuevoTelefono);
            $stm->bindParam(":proveedorId", $proveedorId);

            $stm->execute();

            // Devolver una respuesta JSON de éxito
            echo json_encode(array("success" => true, "message" => "Cambios guardados correctamente"));
            exit();
        } catch (PDOException $e) {
            error_log("Error en la consulta SQL: " . $e->getMessage());

            // Devolver una respuesta JSON con el error
            echo json_encode(array("success" => false, "message" => "Error al guardar cambios: " . $e->getMessage()));
            exit();
        }
    }
}