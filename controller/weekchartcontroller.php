<?php
include_once("../config/conexion.php");

// Obtener la fecha actual
$currentDate = date('Y-m-d');

// Consulta SQL
$sql = "
SELECT
                c.nombre AS Chatarra,
                SUM(dc.cantidad) AS KilosComprados,
                SUM(dc.subtotal) AS MontoTotal
            FROM detallescompra dc
                JOIN chatarra c ON dc.id_chatarra = c.id_chatarra
                JOIN ticketcompra tc ON dc.id_ticketcompra = tc.id_ticketcompra
            WHERE tc.fecha BETWEEN CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY AND CURDATE() + INTERVAL 6 - WEEKDAY(CURDATE()) DAY
            GROUP BY c.nombre
            ORDER BY MontoTotal DESC;
";

// Preparar la consulta
$stmt = $con->prepare($sql);
$stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);

// Ejecutar la consulta
$stmt->execute();

// Obtener los resultados
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($result);
