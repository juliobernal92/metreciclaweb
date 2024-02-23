<?php
include("../config/conexion.php");

// Consulta para obtener la lista de empleados
$consultaLocales = $con->query("SELECT * FROM localesventa WHERE activo=TRUE" );

// Crear un array para almacenar los resultados
$locales = array();

// Recorrer los resultados y almacenarlos en el array
while ($local = $consultaLocales->fetch(PDO::FETCH_ASSOC)) {
    $locales[] = array(
        'id_localventa' => $local['id_localventa'],
        'nombre' => $local['nombre'],
        'direccion' => $local['direccion'],
        'telefono' => $local['telefono']
    );
}

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($locales);
