<?php
include("../config/conexion.php");

// Obtener la fecha de hoy
$hoy = date('Y-m-d');

// Obtener la fecha de inicio de la semana
$inicio_semana = date('Y-m-d', strtotime('monday this week'));

// Obtener la fecha de inicio del mes
$inicio_mes = date('Y-m-01');

// Consulta SQL para obtener la suma del monto de los gastos de hoy
$sql_hoy = "SELECT SUM(monto) AS total_hoy FROM gastos WHERE fecha = :fecha_hoy";
$stmt_hoy = $con->prepare($sql_hoy);
$stmt_hoy->bindParam(':fecha_hoy', $hoy);
$stmt_hoy->execute();
$total_hoy = $stmt_hoy->fetch(PDO::FETCH_ASSOC)['total_hoy'];

// Consulta SQL para obtener la suma del monto de los gastos de la semana
$sql_semana = "SELECT SUM(monto) AS total_semana FROM gastos WHERE fecha >= :inicio_semana AND fecha <= :hoy";
$stmt_semana = $con->prepare($sql_semana);
$stmt_semana->bindParam(':inicio_semana', $inicio_semana);
$stmt_semana->bindParam(':hoy', $hoy);
$stmt_semana->execute();
$total_semana = $stmt_semana->fetch(PDO::FETCH_ASSOC)['total_semana'];

// Consulta SQL para obtener la suma del monto de los gastos del mes
$sql_mes = "SELECT SUM(monto) AS total_mes FROM gastos WHERE fecha >= :inicio_mes AND fecha <= :hoy";
$stmt_mes = $con->prepare($sql_mes);
$stmt_mes->bindParam(':inicio_mes', $inicio_mes);
$stmt_mes->bindParam(':hoy', $hoy);
$stmt_mes->execute();
$total_mes = $stmt_mes->fetch(PDO::FETCH_ASSOC)['total_mes'];

// Crear un array con los totales
$totales = array(
    'total_hoy' => $total_hoy,
    'total_semana' => $total_semana,
    'total_mes' => $total_mes
);

// Devolver los totales como JSON
echo json_encode($totales);
?>
