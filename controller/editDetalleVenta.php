<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Editar detalle de compra (solo cantidad)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (
        isset($_POST["id_detallesventa"]) && !empty($_POST["id_detallesventa"]) &&
        isset($_POST["nuevaCantidad"]) && !empty($_POST["nuevaCantidad"])
    ) {
        $detalleId = $_POST["id_detallesventa"];
        $nuevaCantidad = $_POST["nuevaCantidad"];

        // Obtener precio actual del detalle de compra
        $stmPrecio = $con->prepare("
        SELECT pv.precioventa from detallesventa dv
        JOIN precioventa pv ON pv.id_precioventa = dv.id_precioventa
        WHERE dv.id_detallesventa=:detalleId;
        ");
        $stmPrecio->bindParam(":detalleId", $detalleId);



        try {
            $stmPrecio->execute();
            $precio = $stmPrecio->fetchColumn();
            // Después de ejecutar $stmPrecio->execute();
            error_log("Precio obtenido: " . $precio);
            $subtotal = $nuevaCantidad*$precio;

            // Actualizar la cantidad y recalcular el subtotal
            $stm = $con->prepare("
                UPDATE detallesventa
                SET cantidad = :nuevaCantidad, subtotal = :subtotal
                WHERE id_detallesventa = :detalleId;
            ");
            $stm->bindParam(":nuevaCantidad", $nuevaCantidad);
            $stm->bindParam(":subtotal", $subtotal);
            $stm->bindParam(":detalleId", $detalleId);

            $stm->execute();
            // Después de ejecutar $stm->execute();
            error_log("Detalles de venta actualizados correctamente");


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
