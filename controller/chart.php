<?php
// Importa tu archivo de conexión a la base de datos
include_once("config/conexion.php");

function getDailyChartData() {
    global $con;

    // Consulta SQL para obtener datos diarios
    $sql = "SELECT
                c.nombre AS Chatarra,
                SUM(dc.cantidad) AS KilosComprados,
                SUM(dc.subtotal) AS MontoTotal
            FROM detallescompra dc
                JOIN chatarra c ON dc.id_chatarra = c.id_chatarra
                JOIN ticketcompra tc ON dc.id_ticketcompra = tc.id_ticketcompra
            WHERE tc.fecha = CURDATE()
            GROUP BY c.nombre
            ORDER BY MontoTotal DESC";

    $stmt = $con->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getWeeklyChartData() {
    global $con;

    // Consulta SQL para obtener datos semanales
    $sql = "SELECT
                c.nombre AS Chatarra,
                SUM(dc.cantidad) AS KilosComprados,
                SUM(dc.subtotal) AS MontoTotal
            FROM detallescompra dc
                JOIN chatarra c ON dc.id_chatarra = c.id_chatarra
                JOIN ticketcompra tc ON dc.id_ticketcompra = tc.id_ticketcompra
            WHERE tc.fecha BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND CURDATE()
            GROUP BY c.nombre
            ORDER BY MontoTotal DESC";

    $stmt = $con->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMonthlyChartData() {
    global $con;

    // Consulta SQL para obtener datos mensuales
    $sql = "SELECT
                c.nombre AS Chatarra,
                SUM(dc.cantidad) AS KilosComprados,
                SUM(dc.subtotal) AS MontoTotal
            FROM detallescompra dc
                JOIN chatarra c ON dc.id_chatarra = c.id_chatarra
                JOIN ticketcompra tc ON dc.id_ticketcompra = tc.id_ticketcompra
            WHERE tc.fecha BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE()
            GROUP BY c.nombre
            ORDER BY MontoTotal DESC";

    $stmt = $con->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
