<?php
include("../config/conexion.php");

$consultaChatarras = $con->query("SELECT * FROM chatarra WHERE activo=TRUE");

// Crear un array para almacenar los resultados
$chatarrasArray = array();

// Recorrer los resultados y almacenarlos en el array
while ($chatarra = $consultaChatarras->fetch(PDO::FETCH_ASSOC)) {
    // Agregar el ID de la chatarra al array
    $chatarrasArray[] = array(
        'id' => $chatarra['id_chatarra'], // Asegúrate de que el nombre de la columna sea correcto
        'nombre' => $chatarra['nombre'],
        'precio' => $chatarra['precio']
    );
}

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($chatarrasArray);
