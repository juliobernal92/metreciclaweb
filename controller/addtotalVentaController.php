<?php 
include("../config/sessioncheck.php");
include("../config/conexion.php");
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])){
    if(isset($_POST["idticketventa"]) && !empty($_POST["idticketventa"]) &&
    isset($_POST["total"]) && !empty($_POST["total"]) ){
        $id_ticket = $_POST["idticketventa"];
        $total = $_POST["total"];

        $stm = $con->prepare("INSERT INTO totalventa(id_ticketventa, totalventa) VALUES(:idticket, :total) ");
        $stm ->bindParam(":idticket",$id_ticket);
        $stm ->bindParam(":total",$total);
        try {
            $stm->execute();
            echo json_encode(array("success" => true));
            exit();        
        } catch (PDOException $e) {
            // Debug: Mostrar error en la consola
            error_log("Error en la consulta SQL: ". $e->getMessage());
        }
    }
}