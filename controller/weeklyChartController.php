<?php
include_once("../config/conexion.php");

// Consulta para el gráfico semanal (por simplicidad, ajusta la consulta según tus necesidades)
$sql = "SELECT
            c.nombre AS Chatarra,
            SUM(dc.cantidad) AS KilosComprados,
            SUM(dc.subtotal) AS MontoTotal
        FROM detallescompra dc
            JOIN chatarra c ON dc.id_chatarra = c.id_chatarra
            JOIN ticketcompra tc ON dc.id_ticketcompra = tc.id_ticketcompra
        WHERE tc.fecha >= CURDATE() - INTERVAL 1 WEEK
        GROUP BY c.nombre
        ORDER BY MontoTotal DESC";

$result = $con->query($sql);
$data = $result->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($data);
