<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Obtener detalles de compra por ID de ticket
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["idticket"]) && !empty($_POST["idticket"])) {
        $idTicket = $_POST["idticket"];

        // Obtener detalles de compra y total basados en el ID del ticket
        $stm = $con->prepare("
            SELECT dc.id_detallecompra, c.nombre, dc.cantidad, c.precio, dc.subtotal
            FROM detallescompra dc
            JOIN chatarra c ON dc.id_chatarra = c.id_chatarra
            WHERE dc.id_ticketcompra = :idTicket
        ");
        $stm->bindParam(":idTicket", $idTicket);
        $stm->execute();
        $detalles = $stm->fetchAll(PDO::FETCH_ASSOC);

        $totalStm = $con->prepare("
            SELECT SUM(subtotal) as total
            FROM detallescompra
            WHERE id_ticketcompra = :idTicket
        ");
        $totalStm->bindParam(":idTicket", $idTicket);
        $totalStm->execute();
        $total = $totalStm->fetch(PDO::FETCH_ASSOC)["total"];

        // Devolver una respuesta JSON con los detalles y el total
        echo json_encode(array("success" => true, "detalles" => $detalles, "total" => $total));
        exit();
    }
}
?>