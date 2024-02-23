$(document).ready(function () {
  // Inicializar DataTables una vez que los datos se hayan cargado
  $("#tablaEmpleados").DataTable({
    language: {
      url: "../json/Spanish.json",
    },
  });
});

function showBootstrapAlert(message, type) {
  // Agregar la alerta al DOM
  var alertHtml =
    '<div class="alert alert-' +
    type +
    ' alert-dismissible fade show" role="alert">' +
    message +
    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
    "</div>";
  $("#alertContainer").html(alertHtml);

  // Ocultar la alerta después de 5 segundos
  setTimeout(function () {
    $(".alert").fadeOut();
  }, 5000);
}

function cargarDatosTabla() {
  $.ajax({
    type: "GET",
    url: "../controller/getempleadoscontroller.php",
    dataType: "json",
    success: function (empleados) {
      // Limpiar la tabla antes de agregar nuevos datos
      $("#tablaEmpleados").dataTable().fnClearTable();

      for(var i = 0; i<empleados.length; i++){
        var empleado = empleados[i];
        var row = [
            (i + 1),
            empleado.nombre,
            empleado.apellido,
            empleado.telefono,
            empleado.direccion,
            empleado.cedula,
            "<button class='btn btn-primary btn-edit' data-id='" +
              empleado.id +
              "'>Editar</button> | <button class='btn btn-danger btn-delete' data-id='" +
              empleado.id +
              "'>Eliminar</button>"
        ];
        $("#tablaEmpleados").dataTable().fnAddData(row);
      }
    },
  });
}

// Manejador de eventos para el botón "Editar"
$(document).on("click", ".btn-edit", function () {
    var empleadoId = $(this).data("id");
  
    // Cargar datos del detalle de compra en el modal
    loadEditData(empleadoId);
  
    // Abre el modal
    $("#editModal").modal("show");
  });
  
  function loadEditData(empleadoId) {
    $.ajax({
        type: "POST",
        url: "../controller/getempleadobyid.php",
        data: {
          id_empleado: empleadoId,
          ajax: 1,
        },
        dataType: "json",
        success: function (responseData) {
          if (responseData.success) {
            $("#modalEmpleadoId").val(responseData.empleado.id_empleado);
            $("#modalNombre").val(responseData.empleado.nombre);
            $("#modalCedula").val(responseData.empleado.cedula);
            $("#modalApellido").val(responseData.empleado.apellido);
            $("#modalDireccion").val(responseData.empleado.direccion);
            $("#modalTelefono").val(responseData.empleado.telefono);
          } else {
            console.error(
              "Error al cargar datos para la edición: " + responseData.message
            );
          }
        },
    
        error: function (xhr, status, error) {
          console.error("Error en la llamada AJAX:", status, error);
        },
      });

  }


  // Función para guardar cambios después de la edición
function saveEditEmpleado() {
  // Obtener datos editados desde el modal
  var empleadoId = $("#modalEmpleadoId").val();
  var nuevoNombre = $("#modalNombre").val();
  var nuevoApellido = $("#modalApellido").val();
  var nuevaDireccion = $("#modalDireccion").val();
  var nuevoTelefono = $("#modalTelefono").val();
  var nuevaCedula = $("#modalCedula").val();
  // Puedes hacer una solicitud AJAX para enviar los datos editados al servidor
  $.ajax({
    type: "POST",
    url: "../controller/editempleadocontroller.php",
    data: {
      id_empleado: empleadoId,
      nuevoNombre: nuevoNombre,
      nuevoApellido: nuevoApellido,
      nuevaDireccion: nuevaDireccion,
      nuevoTelefono: nuevoTelefono,
      nuevaCedula: nuevaCedula,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        $("#editModal").modal("hide");
        cargarDatosTabla();
        showBootstrapAlert("Empleado editado correctamente", "success");
      } else {
        console.error("Error al guardar cambios: " + responseData.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la llamada AJAX:", status, error);
    },
  });
}

function cerrarModalEdit() {
  $("#editModal").modal("hide");
}

function cerrarDelete() {
  $("#confirmDeleteModal").modal("hide");
}


// Manejador de eventos para el botón "Eliminar"
$(document).on("click", ".btn-delete", function () {
  var empleadoId = $(this).data("id");

  // Configura el manejador de clic para el botón "Eliminar" en el modal
  $("#confirmDeleteBtn")
    .unbind()
    .on("click", function () {
      // Llama a la función para eliminar la chatarra
      deleteEmpleado(empleadoId);

      // Cierra el modal después de hacer clic en "Eliminar"
      $("#confirmDeleteModal").modal("hide");
    });

  // Abre el modal de confirmación
  $("#confirmDeleteModal").modal("show");
});

function deleteEmpleado(empleadoId) {
  $.ajax({
    type: "POST",
    url: "../controller/deleteempleado.php",
    data: {
      id_empleado: empleadoId,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        cargarDatosTabla();

        // Mostrar la alerta de Bootstrap
        showBootstrapAlert(
          "Empleado eliminado correctamente",
          "success"
        );
      } else {
        console.error(
          "Error al eliminar empleado: " + responseData.message
        );
        // Mostrar la alerta de Bootstrap con un mensaje de error
        showBootstrapAlert(
          "Error al eliminar empleado: " + responseData.message,
          "danger"
        );
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la llamada AJAX:", status, error);
      // Mostrar la alerta de Bootstrap con un mensaje de error
      showBootstrapAlert("Error en la llamada AJAX", "danger");
    },
  });
}