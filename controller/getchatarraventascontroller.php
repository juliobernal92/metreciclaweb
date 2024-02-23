<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Verificar si es una solicitud AJAX
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["action"])) {
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($action === "getOptions") {
        // Obtener las opciones de la tabla "chatarra"
        $stm = $con->query("SELECT id_chatarra, nombre FROM chatarra");
        $chatarraOptions = $stm->fetchAll(PDO::FETCH_ASSOC);

        // Devolver opciones de chatarra como respuesta JSON
        echo json_encode($chatarraOptions);
        exit();
    } elseif ($action === "getDetails" && isset($_GET["idChatarra"])) {
        $idChatarra = filter_input(INPUT_GET, 'idChatarra', FILTER_VALIDATE_INT);

        if ($idChatarra !== false) {
            // Realizar la consulta para obtener detalles de la chatarra
            $stm = $con->prepare("SELECT id_chatarra, precio FROM chatarra WHERE id_chatarra = :idChatarra");
            $stm->bindParam(":idChatarra", $idChatarra, PDO::PARAM_INT);
            $stm->execute();

            $chatarraDetails = $stm->fetch(PDO::FETCH_ASSOC);

            // Devolver detalles de la chatarra como respuesta JSON
            echo json_encode($chatarraDetails);
            exit();
        }
    } elseif ($action === "getPrecioVenta" && isset($_GET["idChatarra"])) {
        $idChatarra = filter_input(INPUT_GET, 'idChatarra', FILTER_VALIDATE_INT);

        if ($idChatarra !== false) {
            // Realizar la consulta para obtener el precio de venta
            $stm = $con->prepare("SELECT precioventa FROM precioventa WHERE id_chatarra = :idChatarra");
            $stm->bindParam(":idChatarra", $idChatarra, PDO::PARAM_INT);
            $stm->execute();

            $precioVenta = $stm->fetch(PDO::FETCH_ASSOC);

            // Devolver el precio de venta como respuesta JSON
            echo json_encode($precioVenta);
            exit();
        }
    }
}

// Si la solicitud no es válida, devolver una respuesta de error
echo json_encode(array("error" => "Solicitud no válida"));
exit();
