<?php
include_once("../config/sessioncheck.php");
include("../templates/header.php");
include("../config/conexion.php");
$token = $_SESSION['token_csrf'];
?>
<div class="container-sm">
    <h3>Añadir Gasto</h3>
    <br />
    <div class="alert-container" name="alertContainer" id="alertContainer"></div>

    <form method="post">
        <!-- Concepto -->
        <div class="col-md-6 mb-3">
            <label for="concepto" class="form-label">Concepto:</label>
            <input type="text" value="" name="concepto" class="form-control" id="concepto" placeholder="Concepto">
            <span class="text-danger"></span>
        </div>
        <!-- Monto -->
        <div class="col-md-6 mb-3">
            <label for="monto" class="form-label">Monto:</label>
            <input type="number" value="" name="monto" class="form-control" id="monto" placeholder="Monto">
            <span class="text-danger"></span>
        </div>

        <!-- Fecha -->
        <div class="col-md-6 mb-3 position-relative" id="fecha-container">
            <label asp-for="Fecha" for="fecha" class="form-label">Fecha:</label>
            <div class="input-group">
                <input type="text" class="form-control" id="fecha" placeholder="Fecha">
                <span class="input-group-text" id="datepicker-icon"><i class="fas fa-calendar-alt"></i></span>
            </div>
            <span class="text-danger"></span>
        </div>

        <div class="col-md-6 mb-3">
            <label for="btnemp" class="form-label"></label>
            <input type="hidden" name="token_csrf" value="<?php echo $token; ?>">
            <button type="button" class="btn btn-success" id="btnemp" onclick="addGastoForm()">Añadir Gasto</button>
        </div>

        <div class="col-md-6 mb-3">
            <a href="/mr/vistas/gastos.php" class="btn btn-secondary">Volver Atrás</a>

        </div>

    </form>
</div>

<div class="col-md-auto mb-3">
    <input type="hidden" class="form-control" name="idempleado" id="idempleado" style="width: 80px;" placeholder="Id" value="<?php echo $id_empleado; ?>" readonly>
</div>

<script src="../js/fecha.js"> </script>
<script src="/mr/js/addgasto.js"></script>
<?php include("../templates/footer.php"); ?>