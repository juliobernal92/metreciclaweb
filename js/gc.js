$(document).ready(function() {

  // Inicializar DataTables una vez que los datos se hayan cargado
  $("#tablaChatarra").DataTable({
      language: {
          url: "../json/Spanish.json"
      }
  });
});



// En lugar de destruir y volver a inicializar la tabla, usar ajax.reload()
function cargarDatosTabla() {
  $.ajax({
      type: "GET",
      url: "../controller/getchatarracontroller.php",
      dataType: "json",
      success: function (chatarras) {
          // Limpiar la tabla antes de agregar nuevos datos
          $("#tablaChatarra").dataTable().fnClearTable();

          // Construir las filas de la tabla con los datos obtenidos
          for (var i = 0; i < chatarras.length; i++) {
              var chatarra = chatarras[i];
              var row = [
                  (i + 1),
                  chatarra.nombre,
                  chatarra.precio,
                  "<button class='btn btn-primary btn-edit' data-id='" + chatarra.id + "'>Editar</button> | <button class='btn btn-danger btn-delete' data-id='" + chatarra.id + "'>Eliminar</button>"
              ];
              $("#tablaChatarra").dataTable().fnAddData(row);
          }
      },
      error: function (xhr, status, error) {
          console.error("Error en la solicitud AJAX:", status, error);
      },
  });
}

// Manejador de eventos para el botón "Editar"
$(document).on("click", ".btn-edit", function () {
  var chatarraId = $(this).data("id");

  // Cargar datos del detalle de compra en el modal
  loadEditData(chatarraId);

  // Abre el modal
  $("#editModal").modal("show");
});

function loadEditData(chatarraId) {
  // Puedes hacer una solicitud AJAX para obtener los datos del detalle de compra
  $.ajax({
    type: "POST",
    url: "../controller/getchatarrabyid.php",
    data: {
      id_chatarra: chatarraId,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        // Lógica para cargar los datos en el modal
        // Por ejemplo, actualizar los campos del formulario en el modal
        $("#modalChatarraId").val(responseData.chatarra.id_chatarra);
        $("#modalNombre").val(responseData.chatarra.nombre);
        $("#modalPrecio").val(responseData.chatarra.precio);
        // ... otros campos del formulario
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
function saveEditChatarra() {
  // Obtener datos editados desde el modal
  var chatarraId = $("#modalChatarraId").val();
  var nuevoNombre = $("#modalNombre").val();
  var nuevoPrecio = $("#modalPrecio").val();

  // Puedes hacer una solicitud AJAX para enviar los datos editados al servidor
  $.ajax({
    type: "POST",
    url: "../controller/editchatarracontroller.php",
    data: {
      id_chatarra: chatarraId,
      nuevoNombre: nuevoNombre,
      nuevoPrecio: nuevoPrecio,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        $("#editModal").modal("hide");
        cargarDatosTabla();
      } else {
        console.error("Error al guardar cambios: " + responseData.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la llamada AJAX:", status, error);
    },
  });
}

function cerrarModalEdit(){
    $("#editModal").modal("hide");
}

function cerrarDelete(){
  $("#confirmDeleteModal").modal("hide");
}






//ELIMINAR
// Manejador de eventos para el botón "Eliminar"
$(document).on("click", ".btn-delete", function () {
  var chatarraId = $(this).data("id");

  // Configura el manejador de clic para el botón "Eliminar" en el modal
  $("#confirmDeleteBtn")
    .unbind()
    .on("click", function () {
      // Llama a la función para eliminar la chatarra
      deleteChatarra(chatarraId);

      // Cierra el modal después de hacer clic en "Eliminar"
      $("#confirmDeleteModal").modal("hide");
    });

  // Abre el modal de confirmación
  $("#confirmDeleteModal").modal("show");
});



// Función para eliminar la chatarra
function deleteChatarra(chatarraId) {
  $.ajax({
    type: "POST",
    url: "../controller/deletechatarra.php",
    data: {
      id_chatarra: chatarraId,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        cargarDatosTabla();

        // Mostrar la alerta de Bootstrap
        showBootstrapAlert(
          "Chatarra eliminada correctamente",
          "success"
        );
      } else {
        console.error(
          "Error al eliminar chatarra: " + responseData.message
        );
        // Mostrar la alerta de Bootstrap con un mensaje de error
        showBootstrapAlert(
          "Error al eliminar chatarra: " + responseData.message,
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