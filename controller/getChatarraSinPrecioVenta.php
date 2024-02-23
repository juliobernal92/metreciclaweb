<?php
// Incluir la configuración de la base de datos y otras dependencias necesarias
include("../config/conexion.php");

// Verificar si se recibió un ID de local
if(isset($_GET['id_local']) && !empty($_GET['id_local'])) {
    $idLocal = $_GET['id_local'];

    // Consultar las chatarras que no están asociadas a un precio en el local seleccionado
    $sql = "SELECT c.id_chatarra, c.nombre 
    FROM chatarra c 
    WHERE c.id_chatarra NOT IN (
        SELECT pv.id_chatarra 
        FROM precioventa pv 
        WHERE pv.id_localventa = :idLocal
    );
    ";

    // Preparar y ejecutar la consulta
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':idLocal', $idLocal, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener los resultados de la consulta
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los resultados en formato JSON
    echo json_encode($result);
} else {
    // Si no se proporcionó un ID de local, devolver un mensaje de error
    echo json_encode(array("error" => "No se proporcionó un ID de local"));
}
?>
