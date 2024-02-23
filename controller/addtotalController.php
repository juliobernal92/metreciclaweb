<?php 
include("../config/sessioncheck.php");
include("../config/conexion.php");
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"])){
    if(isset($_POST["idticket"]) && !empty($_POST["idticket"]) &&
    isset($_POST["total"]) && !empty($_POST["total"]) ){
        $id_ticket = $_POST["idticket"];
        $total = $_POST["total"];

        $stm = $con->prepare("INSERT INTO totalcompra(id_ticketcompra, total) VALUES(:idticket, :total) ");
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