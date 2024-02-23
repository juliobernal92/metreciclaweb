<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Añadimos ticket
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (
        isset($_POST["fecha"]) && !empty($_POST["fecha"]) &&
        isset($_POST["idempleado"]) && !empty($_POST["idempleado"])
    ) {

        $fecha = date("Y-m-d", strtotime($_POST["fecha"]));
        $idempleado = $_POST["idempleado"];

        $stm = $con->prepare("INSERT INTO ticketventa(fecha, id_empleado) VALUES(:fecha, :idempleado)");
        $stm->bindParam(":fecha", $fecha);
        $stm->bindParam(":idempleado", $idempleado);

        try {
            $stm->execute();

            // Obtener el último id insertado
            $lastInsertId = $con->lastInsertId();

            // Debug: Mostrar mensaje de éxito
            error_log("Debug: Ticket añadido correctamente. ID: " . $lastInsertId);

            // Devolver una respuesta JSON con el ID del ticket añadido
            echo json_encode(array("success" => true, "idticketventa" => $lastInsertId));
            exit();
        } catch (PDOException $e) {
            // Debug: Mostrar error en la consola
            error_log("Error en la consulta SQL: " . $e->getMessage());

            // Devolver una respuesta JSON con el error
            echo json_encode(array("success" => false, "message" => "Error al añadir ticket: " . $e->getMessage()));
            exit();
        }
    } else {
        // Devolver una respuesta JSON indicando campos incompletos o vacíos
        echo json_encode(array("success" => false, "message" => "Error: Campos requeridos incompletos o vacíos."));
        exit();
    }
}
