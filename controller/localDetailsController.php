<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

$query = "SELECT id_localventa, nombre FROM localesventa";
$result = $con->query($query);

$locales = array();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $locales[] = $row;
}

header('Content-Type: application/json');
echo json_encode($locales);
