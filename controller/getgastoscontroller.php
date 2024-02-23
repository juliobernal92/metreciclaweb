<?php
include("../config/conexion.php");

$consultaGastos = $con->query("SELECT * FROM gastos");

$gastosArray = array();

while ($gasto = $consultaGastos->fetch(PDO::FETCH_ASSOC)) {
    $gastosArray[] = array(
        'id' => $gasto['id_gasto'], 
        'concepto' => $gasto['concepto'],
        'monto' => $gasto['monto'],
        'fecha' => $gasto['fecha']
    );
}

header('Content-Type: application/json');
echo json_encode($gastosArray);
