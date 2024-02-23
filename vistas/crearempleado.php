<?php
include_once("../config/sessioncheck.php");
include("../templates/header.php");
include("../config/conexion.php");
$token =$_SESSION['token_csrf'];
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

            <!-- Apellido -->
            <div class="col-md-6 mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" value="" name="apellido" class="form-control" id="apellido" placeholder="Apellido">
                <span class="text-danger"></span>
            </div>
        </div>

        <div class="row">
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

        <div class="row">
            <!-- Cedula -->
            <div class="col-md-6 mb-3">
                <label for="cedula" class="form-label">Cedula:</label>
                <input type="number" value="" name="cedula" class="form-control" id="cedula" placeholder="Cedula">
                <span class="text-danger"></span>
            </div>

            <!-- Contraseña -->
            <div class="col-md-6 mb-3">
                <label for="contraseña" class="form-label">Contraseña:</label>
                <input type="password" value="" name="contraseña" class="form-control" id="contraseña" placeholder="Contraseña">
                <span class="text-danger"></span>
            </div>
        </div>

         <!-- Botones de añadir y volver atrás en la misma línea -->
         <div class="row justify-content-center">
            <div class="col-md-6 mb-3 d-flex justify-content-center align-items-center">
                <label for="btnemp" class="form-label"></label>
                <input type="hidden" name="token_csrf" value="<?php echo $token; ?>">
                <button type="button" class="btn btn-success" id="btnemp" onclick="addEmpleado()">Crear Empleado</button>

                <a href="/mr/vistas/empleados.php" class="btn btn-secondary ms-2">Volver Atrás</a>
            </div>
        </div>
    </form>
</div>

<script src="/mr/js/ae.js"></script>
<?php include("../templates/footer.php"); ?>