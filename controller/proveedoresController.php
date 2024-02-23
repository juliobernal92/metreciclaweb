<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    // Validar y sanitizar datos
    $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $telefono = filter_var($_POST["telefono"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $direccion = filter_var($_POST["direccion"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Verificar si los datos son válidos
    if (!empty($nombre) && !empty($telefono) && !empty($direccion)) {
        $stm = $con->prepare("INSERT INTO proveedores(nombre, telefono, direccion) VALUES(:nombre, :telefono, :direccion)");
        $stm->bindParam(":nombre", $nombre);
        $stm->bindParam(":telefono", $telefono);
        $stm->bindParam(":direccion", $direccion);

        try {
            $stm->execute();
            $lastInsertId = $con->lastInsertId();

            echo json_encode(array("success" => true, "idProveedor" => $lastInsertId));
            exit();
        } catch (PDOException $e) {
            echo json_encode(array("success" => false, "message" => "Error al añadir proveedor: " . $e->getMessage()));
            exit();
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Error: Campos requeridos incompletos o vacíos."));
        exit();
    }
}