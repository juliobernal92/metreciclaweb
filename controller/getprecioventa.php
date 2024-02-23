<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Verificar si es una solicitud AJAX
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["idChatarra"]))  {
    $idChatarra = filter_input(INPUT_GET, 'idChatarra', FILTER_VALIDATE_INT);

    if ($idChatarra !== false) {
        // Realizar la consulta para obtener el precio de venta
        $stm = $con->prepare("SELECT id_precioventa, precioventa FROM precioventa WHERE id_chatarra = :idChatarra");
        $stm->bindParam(":idChatarra", $idChatarra, PDO::PARAM_INT);
        $stm->execute();

        $precioVenta = $stm->fetch(PDO::FETCH_ASSOC);

        if ($precioVenta) {
            // Devolver el precio de venta como respuesta JSON
            echo json_encode($precioVenta);
            exit();
        } else {
            // Devolver un error si la chatarra no se encuentra en el local seleccionado
            echo json_encode(array("error" => "Chatarra no encontrada en el local seleccionado."));
            exit();
        }
    }
}

// Si la solicitud no es válida, devolver una respuesta de error
echo json_encode(array("error" => "Solicitud no válida"));
exit();
