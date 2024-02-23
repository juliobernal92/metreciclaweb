<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Obtener detalles de venta y total
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["idticketventa"]) && !empty($_POST["idticketventa"])) {
        $idTicket = $_POST["idticketventa"];

        // Consulta SQL para obtener los detalles de venta
        $detallesVentaStmt = $con->prepare("
        SELECT dv.id_detallesventa, c.nombre AS nombre_chatarra, dv.cantidad, pv.precioventa, dv.subtotal
        FROM detallesventa dv
        JOIN precioventa pv ON dv.id_precioventa = pv.id_precioventa
        JOIN chatarra c ON pv.id_chatarra = c.id_chatarra
        WHERE dv.id_ticketventa = :idticketventa;        
        ");
        $detallesVentaStmt->bindParam(":idticketventa", $idTicket);
        $detallesVentaStmt->execute();
        $detallesVenta = $detallesVentaStmt->fetchAll(PDO::FETCH_ASSOC);

        // Consulta SQL para obtener el total de la venta
        $totalVentaStmt = $con->prepare("
        SELECT SUM(subtotal) as total
        FROM detallesventa
        WHERE id_ticketventa = :idticketventa;
        ");
        $totalVentaStmt->bindParam(":idticketventa", $idTicket);
        $totalVentaStmt->execute();
        $totalVenta = $totalVentaStmt->fetch(PDO::FETCH_ASSOC)["total"];

        // Devolver una respuesta JSON con los detalles de venta y el total
        echo json_encode(array("success" => true, "detalles" => $detallesVenta, "total" => $totalVenta));
        die();
    }
}
