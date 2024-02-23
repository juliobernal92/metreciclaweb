<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Editar detalle de compra (solo cantidad)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (
        isset($_POST["id_detallecompra"]) && !empty($_POST["id_detallecompra"]) &&
        isset($_POST["nuevaCantidad"]) && !empty($_POST["nuevaCantidad"])
    ) {
        $detalleId = $_POST["id_detallecompra"];
        $nuevaCantidad = $_POST["nuevaCantidad"];

        // Obtener precio actual del detalle de compra
        $stmPrecio = $con->prepare("
            SELECT c.precio
            FROM detallescompra d
            INNER JOIN chatarra c ON d.id_chatarra = c.id_chatarra
            WHERE d.id_detallecompra = :detalleId
        ");
        $stmPrecio->bindParam(":detalleId", $detalleId);

        try {
            $stmPrecio->execute();
            $precio = $stmPrecio->fetchColumn();

            // Actualizar la cantidad y recalcular el subtotal
            $stm = $con->prepare("
                UPDATE detallescompra
                SET cantidad = :nuevaCantidad, subtotal = :nuevaCantidad * :precio
                WHERE id_detallecompra = :detalleId
            ");
            $stm->bindParam(":nuevaCantidad", $nuevaCantidad);
            $stm->bindParam(":precio", $precio);
            $stm->bindParam(":detalleId", $detalleId);

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