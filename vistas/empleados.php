<?php
include("../config/sessioncheck.php");
include("../templates/header.php");
include("../config/conexion.php");
?>
<div class="container-sm">
    <h4>Tabla de Empleados</h4>
    <br />
    <a href="../vistas/crearempleado.php" class="btn btn-secondary">Crear Empleado</a>
    <br>
    <div id="alertContainer"></div>
    <br />
    <table class="table " id="tablaEmpleados">
        <!-- ... Cabecera de la tabla ... -->
        <thead class="table table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Dirección</th>
                <th scope="col">Cedula</th>
                <th scope="col">Acción</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí se insertarán las filas de empleados usando JavaScript -->
        </tbody>
    </table>
</div>



<!--MODALES-->
<!-- Modal para la Edición -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Información del Empleado</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="cerrarModalEdit()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar Empleado -->
                <form id="editForm">
                    <input type="hidden" id="modalEmpleadoId" name="modalEmpleadoId" value="">
                    <div class="mb-3">
                        <label for="modalNombre" class="form-label">Nuevo Nombre:</label>
                        <input type="text" class="form-control" id="modalNombre" name="modalNombre" placeholder="Nuevo Nombre">
                    </div>

                    <div class="mb-3">
                        <label for="modalApellido" class="form-label">Nuevo Apellido:</label>
                        <input type="text" class="form-control" id="modalApellido" name="modalApellido" placeholder="Nuevo Apellido">
                    </div>

                    <div class="mb-3">
                        <label for="modalTelefono" class="form-label">Nuevo Telefono:</label>
                        <input type="text" class="form-control" id="modalTelefono" name="modalTelefono" placeholder="Nuevo Telefono">
                    </div>

                    <div class="mb-3">
                        <label for="modalDireccion" class="form-label">Nueva Direccion:</label>
                        <input type="text" class="form-control" id="modalDireccion" name="modalDireccion" placeholder="Nueva Direccion">
                    </div>

                    <div class="mb-3">
                        <label for="modalCedula" class="form-label">Cedula:</label>
                        <input type="text" class="form-control" id="modalCedula" name="modalCedula" placeholder="Cedula" readonly>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="cerrarModalEdit()">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveEditEmpleado()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" onclick="cerrarDelete()" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar el Empleado?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarDelete()" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script src="/mr/js/ge.js"></script>
<script src="/mr/js/ae.js"></script>
<script src="../json/Spanish.json"></script>
<script>
    $(document).ready(function() {
        cargarDatosTabla();
    });
</script>
<?php include("../templates/footer.php"); ?>