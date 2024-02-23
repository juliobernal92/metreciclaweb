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
        <div class="row">
            <!-- Nombre -->
            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" value="" name="nombre" class="form-control" id="nombre" placeholder="Nombre">
                <span class="text-danger"></span>
            </div>
            <!-- Teléfono -->
            <div class="col-md-6 mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="number" value="" name="telefono" class="form-control" id="telefono" placeholder="Telefono">
                <span class="text-danger"></span>
            </div>

            <!-- Dirección -->
            <div class="col-md-6 mb-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" value="" name="direccion" class="form-control" id="direccion" placeholder="Dirección">
                <span class="text-danger"></span>
            </div>

        </div>

        

        <!-- Botones de añadir y volver atrás en la misma línea -->
        <div class="row justify-content-center">
            <div class="col-md-6 mb-3 d-flex justify-content-center align-items-center">
                <label for="btnemp" class="form-label"></label>
                <input type="hidden" name="token_csrf" value="<?php echo $token; ?>">
                <button type="button" class="btn btn-success" id="btnemp" onclick="addProveedorForm()">Añadir Proveedor</button>

                <a href="/mr/vistas/proveedores.php" class="btn btn-secondary ms-2">Volver Atrás</a>
            </div>
        </div>
    </form>
</div>

<script src="/mr/js/ap.js"></script>
<?php include("../templates/footer.php"); ?>