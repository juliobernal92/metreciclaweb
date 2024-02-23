<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Obtener detalle de compra por ID
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["id_localventa"]) && !empty($_POST["id_localventa"])) {
        $localId = $_POST["id_localventa"];

        // Obtener detalles de compra basado en el ID
        $stm = $con->prepare("
            SELECT id_localventa, nombre, direccion, telefono
            FROM localesventa
            WHERE id_localventa = :localId
        ");
        $stm->bindParam(":localId", $localId);
        $stm->execute();
        $local = $stm->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró un local con el ID proporcionado
        if ($local) {
            // Devolver una respuesta JSON con el detalle del local encontrado
            echo json_encode(array("success" => true, "local" => $local));
        } else {
            // Devolver una respuesta JSON indicando que no se encontró ningún local con el ID proporcionado
            echo json_encode(array("success" => false, "message" => "No se encontró ningún local con el ID proporcionado."));
        }
        exit();
    }
}
