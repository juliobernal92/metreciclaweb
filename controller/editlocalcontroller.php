<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (
        isset($_POST["id_localventa"]) && !empty($_POST["id_localventa"]) &&
        isset($_POST["nuevoNombre"]) && !empty($_POST["nuevoNombre"] &&
        isset($_POST["nuevaDireccion"]) &&!empty($_POST["nuevaDireccion"]) &&
        isset($_POST["nuevoTelefono"]) &&!empty($_POST["nuevoTelefono"])        
        )
    ) {
        $localId = $_POST["id_localventa"];
        $nuevoNombre = $_POST["nuevoNombre"];
        $nuevaDireccion = $_POST["nuevaDireccion"];
        $nuevoTelefono = $_POST["nuevoTelefono"];

        try {
            $stm = $con->prepare("
                UPDATE localesventa
                SET nombre = :nuevoNombre, direccion = :nuevaDireccion, telefono = :nuevoTelefono 
                WHERE id_localventa = :localId;
            ");
            $stm->bindParam(":nuevoNombre", $nuevoNombre);
            $stm->bindParam(":nuevaDireccion", $nuevaDireccion);
            $stm->bindParam(":nuevoTelefono", $nuevoTelefono);
            $stm->bindParam(":localId", $localId);

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