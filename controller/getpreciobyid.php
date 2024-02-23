<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Obtener detalle de compra por ID
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["id_precioventa"]) && !empty($_POST["id_precioventa"])) {
        $precioId = $_POST["id_precioventa"];

        $stm = $con->prepare("
        SELECT c.nombre, pv.precioventa FROM precioventa pv 
        JOIN chatarra c on pv.id_chatarra = c.id_chatarra 
        WHERE pv.id_precioventa=:precioId;
        ");
        $stm->bindParam(":precioId", $precioId);
        $stm->execute();
        $precio = $stm->fetch(PDO::FETCH_ASSOC);

        // Devolver una respuesta JSON con el detalle
        echo json_encode(array("success" => true, "precio" => $precio));
        exit();
    }
}
