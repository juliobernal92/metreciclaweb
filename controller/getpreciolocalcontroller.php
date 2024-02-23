<?php
// Verificar si la solicitud es de tipo GET y si se proporcionó el ID del local
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id_local"])) {
    // Incluir archivo de configuración de la base de datos y otras dependencias
    include("../config/conexion.php");

    // Obtener el ID del local desde la solicitud
    $idLocal = $_GET["id_local"];

    // Preparar la consulta SQL para obtener los detalles de los precios del local seleccionado
    $query = "SELECT pv.id_precioventa, pv.id_chatarra, c.nombre, pv.precioventa AS precio, c.activo
              FROM precioventa pv 
              JOIN chatarra c ON pv.id_chatarra = c.id_chatarra
              WHERE pv.id_localventa = :id_local AND c.activo=TRUE AND pv.activo=TRUE";

    // Preparar la sentencia
    $statement = $con->prepare($query);

    // Vincular el parámetro
    $statement->bindParam(":id_local", $idLocal);

    // Ejecutar la consulta
    if ($statement->execute()) {
        // Obtener los resultados de la consulta
        $precios = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Devolver los resultados como JSON
        echo json_encode($precios);
    } else {
        // Si hay un error al ejecutar la consulta, devolver un mensaje de error
        http_response_code(500);
        echo json_encode(array("message" => "Error al obtener los detalles de los precios del local."));
    }
} else {
    // Si la solicitud no es de tipo GET o no se proporcionó el ID del local, devolver un mensaje de error
    http_response_code(400);
    echo json_encode(array("message" => "Solicitud inválida."));
}
?>
