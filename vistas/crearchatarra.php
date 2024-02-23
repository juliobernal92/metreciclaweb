<?php
include_once("../config/sessioncheck.php");
include("../templates/header.php");
include("../config/conexion.php");
$token = $_SESSION['token_csrf'];
?>
<div class="container-sm">
    <br />
    <div class="alert-container" name="alertContainer" id="alertContainer"></div>

    <form method="post">
        <!-- Nombre -->
        <div class="col-md-6 mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" value="" name="nombre" class="form-control" id="nombre" placeholder="Nombre">
            <span class="text-danger"></span>
        </div>
        <!-- Precio -->
        <div class="col-md-6 mb-3">
            <label for="precio" class="form-label">Precio:</label>
            <input type="number" value="" name="precio" class="form-control" id="precio" placeholder="Precio">
            <span class="text-danger"></span>
        </div>

        <div class="col-md-6 mb-3">
            <label for="btnemp" class="form-label"></label>
            <input type="hidden" name="token_csrf" value="<?php echo $token; ?>">
            <button type="button" class="btn btn-success" id="btnemp" onclick="addChatarraForm()">Añadir Chatarra</button>
        </div>

        <div class="col-md-6 mb-3">
            <a href="/mr/vistas/chatarra.php" class="btn btn-secondary">Volver Atrás</a>

        </div>

    </form>
</div>

<script src="/mr/js/ac.js"></script>
<?php include("../templates/footer.php"); ?>