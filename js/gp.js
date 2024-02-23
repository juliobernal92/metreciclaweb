$(document).ready(function () {
  // Inicializar DataTables una vez que los datos se hayan cargado
  $("#tablaChatarra").DataTable({
    language: {
      url: "../json/Spanish.json",
    },
  });
});

function cargarDatosTabla() {
  $.ajax({
    type: "GET",
    url: "../controller/getproveedorescontroller.php",
    dataType: "json",
    success: function (proveedores) {
      // Limpiar la tabla antes de agregar nuevos datos
      $("#tablaProveedores").dataTable().fnClearTable();

      for (var i = 0; i < proveedores.length; i++) {
        var proveedor = proveedores[i];
        var row = [
          i + 1,
          proveedor.nombre,
          proveedor.direccion,
          proveedor.telefono,
          "<button class='btn btn-primary btn-edit' data-id='" +
            proveedor.id +
            "'>Editar</button> | <button class='btn btn-danger btn-delete' data-id='" +
            proveedor.id +
            "'>Eliminar</button>",
        ];
        $("#tablaProveedores").dataTable().fnAddData(row);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", status, error);
    },
  });
}

// Manejador de eventos para el botón "Editar"
$(document).on("click", ".btn-edit", function () {
  var proveedorId = $(this).data("id");

  // Cargar datos del detalle de compra en el modal
  loadEditData(proveedorId);

  // Abre el modal
  $("#editModal").modal("show");
});

function loadEditData(proveedorId) {
  $.ajax({
    type: "POST",
    url: "../controller/getproveedorbyid.php",
    data: {
      id_proveedor: proveedorId,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        $("#modalProveedorId").val(responseData.proveedor.id_proveedor);
        $("#modalNombre").val(responseData.proveedor.nombre);
        $("#modalDireccion").val(responseData.proveedor.direccion);
        $("#modalTelefono").val(responseData.proveedor.telefono);
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
function saveEditProveedor() {
  // Obtener datos editados desde el modal
  var proveedorId = $("#modalProveedorId").val();
  var nuevoNombre = $("#modalNombre").val();
  var nuevaDireccion = $("#modalDireccion").val();
  var nuevoTelefono = $("#modalTelefono").val();
  // Puedes hacer una solicitud AJAX para enviar los datos editados al servidor
  $.ajax({
    type: "POST",
    url: "../controller/editproveedorcontroller.php",
    data: {
      id_proveedor: proveedorId,
      nuevoNombre: nuevoNombre,
      nuevaDireccion: nuevaDireccion,
      nuevoTelefono: nuevoTelefono,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        $("#editModal").modal("hide");
        cargarDatosTabla();
        showBootstrapAlert("Proveedor editado correctamente", "success");
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
    var proveedorId = $(this).data("id");
  
    // Configura el manejador de clic para el botón "Eliminar" en el modal
    $("#confirmDeleteBtn")
      .unbind()
      .on("click", function () {
        // Llama a la función para eliminar la chatarra
        deleteProveedor(proveedorId);
  
        // Cierra el modal después de hacer clic en "Eliminar"
        $("#confirmDeleteModal").modal("hide");
      });
  
    // Abre el modal de confirmación
    $("#confirmDeleteModal").modal("show");
  });
  
  function deleteProveedor(proveedorId) {
    $.ajax({
        type: "POST",
        url: "../controller/deleteproveedor.php",
        data: {
          id_proveedor: proveedorId,
          ajax: 1,
        },
        dataType: "json",
        success: function (responseData) {
          if (responseData.success) {
            cargarDatosTabla();
    
            // Mostrar la alerta de Bootstrap
            showBootstrapAlert(
              "Proveedor eliminado correctamente",
              "success"
            );
          } else {
            console.error(
              "Error al eliminar proveedor: " + responseData.message
            );
            // Mostrar la alerta de Bootstrap con un mensaje de error
            showBootstrapAlert(
              "Error al eliminar proveedor: " + responseData.message,
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