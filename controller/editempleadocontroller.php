<?php
header('Content-Type: application/json');
include("../config/sessioncheck.php");
include("../config/conexion.php");

// Editar detalle de compra (solo cantidad)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])) {
    if (
        isset($_POST["id_empleado"]) && !empty($_POST["id_empleado"]) &&
        isset($_POST["nuevoNombre"]) && !empty($_POST["nuevoNombre"] &&
        isset($_POST["nuevoApellido"]) && !empty($_POST["nuevoApellido"]) &&
        isset($_POST["nuevaDireccion"]) &&!empty($_POST["nuevaDireccion"]) &&
        isset($_POST["nuevoTelefono"]) &&!empty($_POST["nuevoTelefono"]) &&
        isset($_POST["nuevaCedula"]) && !empty($_POST["nuevaCedula"])        
        )
    ) {
        $empleadoId = $_POST["id_empleado"];
        $nuevoNombre = $_POST["nuevoNombre"];
        $nuevoApellido = $_POST["nuevoApellido"];
        $nuevaDireccion = $_POST["nuevaDireccion"];
        $nuevoTelefono = $_POST["nuevoTelefono"];
        $nuevaCedula = $_POST["nuevaCedula"];

        try {
            // Actualizar la cantidad y recalcular el subtotal
            $stm = $con->prepare("
                UPDATE empleados
                SET nombre = :nuevoNombre, apellido = :nuevoApellido, direccion = :nuevaDireccion, telefono = :nuevoTelefono, cedula = :nuevaCedula 
                WHERE id_empleado = :empleadoId;
            ");
            $stm->bindParam(":nuevoNombre", $nuevoNombre);
            $stm->bindParam(":nuevoApellido", $nuevoApellido);
            $stm->bindParam(":nuevaDireccion", $nuevaDireccion);
            $stm->bindParam(":nuevoTelefono", $nuevoTelefono);
            $stm->bindParam(":empleadoId", $empleadoId);
            $stm->bindParam(":nuevaCedula", $nuevaCedula);

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