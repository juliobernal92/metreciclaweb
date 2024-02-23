<?php
include("../config/conexion.php");

// Consulta para obtener la lista de empleados
$consultaEmpleados = $con->query("SELECT * FROM empleados WHERE activo=TRUE");

// Crear un array para almacenar los resultados
$empleados = array();

// Recorrer los resultados y almacenarlos en el array
while ($empleado = $consultaEmpleados->fetch(PDO::FETCH_ASSOC)) {
    $empleados[] = array(
        'id' => $empleado['id_empleado'],
        'nombre' => $empleado['nombre'],
        'apellido' => $empleado['apellido'], // Utiliza $empleado en lugar de $apellido
        'direccion' => $empleado['direccion'],
        'telefono' => $empleado['telefono'],
        'cedula' => $empleado['cedula'] // Utiliza $empleado en lugar de $cedula
    );
}


// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($empleados);
