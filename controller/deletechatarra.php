<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Eliminar detalle de compra
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["id_chatarra"]) && !empty($_POST["id_chatarra"])) {
        $chatarraId = $_POST["id_chatarra"];

        // Eliminar el detalle de compra
        $stm = $con->prepare("UPDATE chatarra SET activo = FALSE WHERE id_chatarra = :chatarraId;");
        $stm->bindParam(":chatarraId", $chatarraId);

        try {
            $stm->execute();

            // Devolver una respuesta JSON de éxito
            echo json_encode(array("success" => true, "message" => "Chatarra eliminada correctamente"));
            exit();
        } catch (PDOException $e) {
            error_log("Error en la consulta SQL: " . $e->getMessage());

            // Devolver una respuesta JSON con el error
            echo json_encode(array("success" => false, "message" => "Error al eliminar la chatarra: " . $e->getMessage()));
            exit();
        }
    }
}