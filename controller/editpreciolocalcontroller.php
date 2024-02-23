<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (
        isset($_POST["id_precioventa"]) && !empty($_POST["id_precioventa"]) &&
        isset($_POST["nuevoPrecio"]) && !empty($_POST["nuevoPrecio"])
    ) {
        $precioId = $_POST["id_precioventa"];
        $nuevoPrecio = $_POST["nuevoPrecio"];


        try {
            // Actualizar la cantidad y recalcular el subtotal
            $stm = $con->prepare("
                UPDATE precioventa
                SET precioventa = :nuevoPrecio 
                WHERE id_precioventa = :precioId
            ");
            $stm->bindParam(":nuevoPrecio", $nuevoPrecio);
            $stm->bindParam(":precioId", $precioId);

            $stm->execute();

            // Devolver una respuesta JSON de éxito
            echo json_encode(array("success" => true, "message" => "Cambios guardados correctamente"));
            exit();
        } catch (PDOException $e) {
            error_log("Error en la consulta SQL: " . $e->getMessage());

            // Devolver una respuesta JSON con el error
            echo json_encode(array("success" => false, "message" => "Error al guardar cambios: " . $e->getMessage()));
            exit();
        }
    }
}
