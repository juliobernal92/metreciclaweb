<?php
include("../config/conexion.php");

$consultaProveedores = $con->query("SELECT * FROM proveedores Where activo=TRUE");

// Crear un array para almacenar los resultados
$proveedoresArray = array();

// Recorrer los resultados y almacenarlos en el array
while ($proveedor = $consultaProveedores->fetch(PDO::FETCH_ASSOC)) {
    $proveedoresArray[] = array(
        'id' => $proveedor['id_proveedor'], // Asegúrate de que el nombre de la columna sea correcto
        'nombre' => $proveedor['nombre'],
        'direccion' => $proveedor['direccion'],
        'telefono' => $proveedor['telefono']

    );
}

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($proveedoresArray);
