<?php
include("../config/sessioncheck.php");
include("../config/conexion.php");
//añadir preciocompra
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])){
    if(isset($_POST["id_local"]) && !empty($_POST["id_local"]) &&
    isset($_POST["id_chatarra"]) && !empty($_POST["id_chatarra"]) &&
    isset($_POST["precio"]) && !empty($_POST["precio"])){
        $id_local = $_POST["id_local"];
        $id_chatarra = $_POST["id_chatarra"];
        $precio = $_POST["precio"];

        $stm = $con->prepare("INSERT INTO precioventa(id_localventa, id_chatarra, precioventa) VALUES(:id_local, :id_chatarra, :precio)");
        $stm->bindParam(":id_local", $id_local);
        $stm->bindParam(":id_chatarra", $id_chatarra);
        $stm->bindParam(":precio", $precio);
        try {
            $stm->execute();
            echo json_encode(array("success" => true, "message" => "Precio añadido correctamente"));
            exit();
        } catch (PDOException $e) {
            // Debug: Mostrar error en la consola
            error_log("Error en la consulta SQL: ". $e->getMessage());
    }

    }
}
