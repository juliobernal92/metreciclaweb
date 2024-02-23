<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Obtener detalle de compra por ID
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (isset($_POST["id_proveedor"]) && !empty($_POST["id_proveedor"])) {
        $proveedorId = $_POST["id_proveedor"];

        // Obtener detalles de compra basado en el ID
        $stm = $con->prepare("
            SELECT id_proveedor, nombre, direccion, telefono
            FROM proveedores
            WHERE id_proveedor = :proveedorId;
        ");
        $stm->bindParam(":proveedorId", $proveedorId);
        $stm->execute();
        $proveedor = $stm->fetch(PDO::FETCH_ASSOC);

        // Devolver una respuesta JSON con el detalle
        $response = array("success" => true, "proveedor" => $proveedor);
        echo json_encode($response);
        exit();
    }
}
