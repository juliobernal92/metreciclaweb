<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Validar y sanitizar el ID del proveedor
if (isset($_POST["idvendedor"]) && !empty($_POST["idvendedor"])) {
    $idProveedor = filter_var($_POST["idvendedor"], FILTER_VALIDATE_INT);

    if ($idProveedor !== false) {
        // Buscar el proveedor en la base de datos
        $stm = $con->prepare("SELECT * FROM proveedores WHERE id_proveedor = :idProveedor");
        $stm->bindParam(":idProveedor", $idProveedor);
        $stm->execute();
        $proveedor = $stm->fetch(PDO::FETCH_ASSOC);

        if ($proveedor) {
            // El proveedor se encontró, devolver la información
            echo json_encode(array("success" => true, "proveedor" => $proveedor));
            exit();
        } else {
            // El proveedor no se encontró
            echo json_encode(array("success" => false, "message" => "Proveedor no encontrado"));
            exit();
        }
    }
}

// Si el ID del proveedor no es válido o no se proporcionó
echo json_encode(array("success" => false, "message" => "ID de proveedor no válido"));
exit();