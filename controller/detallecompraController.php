<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Añadir detallecompra
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (
        isset($_POST["idticket"]) && !empty($_POST["idticket"]) &&
        isset($_POST["idChatarra"]) && !empty($_POST["idChatarra"]) &&
        isset($_POST["cantidad"]) && !empty($_POST["cantidad"]) &&
        isset($_POST["subtotal"]) && !empty($_POST["subtotal"])
    ) {
        $idticket = $_POST["idticket"];
        $idChatarra = $_POST["idChatarra"];
        $cantidad = $_POST["cantidad"];
        $subtotal = $_POST["subtotal"];

        $stm = $con->prepare("INSERT INTO detallescompra(id_ticketcompra, id_chatarra, cantidad, subtotal) VALUES(:idticket, :idChatarra, :cantidad, :subtotal)");
        $stm->bindParam(":idticket", $idticket);
        $stm->bindParam(":idChatarra", $idChatarra);
        $stm->bindParam(":cantidad", $cantidad);
        $stm->bindParam(":subtotal", $subtotal);

        try {
            $stm->execute();
            $lastInsertId = $con->lastInsertId();
            
            // Devolver una respuesta JSON de éxito
            echo json_encode(array("success" => true, "message" => "Detalle de compra añadido correctamente"));
            exit();
        } catch (PDOException $e) {
            error_log("Error en la consulta SQL: " . $e->getMessage());

            // Devolver una respuesta JSON con el error
            echo json_encode(array("success" => false, "message" => "Error al añadir detalle de compra: " . $e->getMessage()));
            exit();
        }
    }
}