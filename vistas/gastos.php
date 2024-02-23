<?php
include("../config/sessioncheck.php");
include("../templates/header.php");
include("../config/conexion.php");
?>

<div class="container-sm">
    <h4>Gastos</h4>
    <br>
    <a href="../vistas/creargasto.php" class="btn btn-secondary">Añadir gasto</a>
    <br>
    <br/>
    <div id="gastoshoy">
        <h5>Gastos:</h5>
        <p>Hoy: </p>
        <p>Semana:</p>
        <p>Mes:</p>
    </div>
    <br>

    <table class="table dataTable" id="tablaGastos">
        <thead class="table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Concepto</th>
                <th scope="col">Monto</th>
                <th scope="col">Fecha</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>





<script src="/mr/js/getgastos.js"></script>
<script src="../json/Spanish.json"></script>
<script>
    $(document).ready(function() {
        cargarDatosTabla();
        obtenerTotales();
    });
</script>


<?php include("../templates/footer.php"); ?>