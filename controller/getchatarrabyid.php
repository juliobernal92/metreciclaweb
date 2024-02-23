<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Obtener detalle de compra por ID
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["id_chatarra"]) && !empty($_POST["id_chatarra"])) {
        $chatarraId = $_POST["id_chatarra"];

        // Obtener detalles de compra basado en el ID
        $stm = $con->prepare("
            SELECT id_chatarra, nombre, precio
            FROM chatarra
            WHERE id_chatarra = :chatarraId
        ");
        $stm->bindParam(":chatarraId", $chatarraId);
        $stm->execute();
        $chatarra = $stm->fetch(PDO::FETCH_ASSOC);

        // Devolver una respuesta JSON con el detalle
        echo json_encode(array("success" => true, "chatarra" => $chatarra));
        exit();
    }
}