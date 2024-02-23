<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Editar detalle de compra (solo cantidad)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (
        isset($_POST["id_chatarra"]) && !empty($_POST["id_chatarra"]) &&
        isset($_POST["nuevoNombre"]) && !empty($_POST["nuevoNombre"] &&
        isset($_POST["nuevoPrecio"]) &&!empty($_POST["nuevoPrecio"])        
        )
    ) {
        $chatarraId = $_POST["id_chatarra"];
        $nuevoNombre = $_POST["nuevoNombre"];
        $nuevoPrecio = $_POST["nuevoPrecio"];


        try {
            // Actualizar la cantidad y recalcular el subtotal
            $stm = $con->prepare("
                UPDATE chatarra
                SET nombre = :nuevoNombre, precio = :nuevoPrecio 
                WHERE id_chatarra = :chatarraId
            ");
            $stm->bindParam(":nuevoNombre", $nuevoNombre);
            $stm->bindParam(":nuevoPrecio", $nuevoPrecio);
            $stm->bindParam(":chatarraId", $chatarraId);

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