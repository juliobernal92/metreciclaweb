<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Obtener detalle de compra por ID
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["id_detallecompra"]) && !empty($_POST["id_detallecompra"])) {
        $detalleId = $_POST["id_detallecompra"];

        // Obtener detalles de compra basado en el ID
        $stm = $con->prepare("
            SELECT id_detallecompra, cantidad
            FROM detallescompra
            WHERE id_detallecompra = :detalleId
        ");
        $stm->bindParam(":detalleId", $detalleId);
        $stm->execute();
        $detalle = $stm->fetch(PDO::FETCH_ASSOC);

        // Devolver una respuesta JSON con el detalle
        echo json_encode(array("success" => true, "detalle" => $detalle));
        exit();
    }
}