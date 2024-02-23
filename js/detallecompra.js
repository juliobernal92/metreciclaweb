function addDetalleCompra() {
  // Obtener los valores del formulario
  var idticket = $("#idticket").val();
  var idchatarra = $("#idChatarra").val();
  var cantidad = $("#cantidad").val();
  var precio = $("#precio").val();
  var subtotal = cantidad * precio;

  $.ajax({
    type: "POST",
    url: "../controller/detallecompraController.php",
    data: {
      idticket: idticket,
      idChatarra: idchatarra, // Ajustar el nombre del parámetro a idChatarra
      cantidad: cantidad,
      subtotal: subtotal,
      ajax: 1, // Agregar el parámetro ajax=1 para identificar la solicitud AJAX
    },
    dataType: "json", // Esperar una respuesta en formato JSON
    success: function (responseData) {
      // Manejar la respuesta del servidor
      if (responseData.success) {
        // Limpiar los campos después de agregar el detalle de compra
        $("#cantidad").val("");
        loadDetallesCompra(idticket);
        // Mostrar la alerta de Bootstrap
        showBootstrapAlert("Chatarra añadida correctamente", "success");
      } else {
        // Error: Mostrar el mensaje de error
        console.error(
          "Error al añadir detalle de compra: " + responseData.message
        );
        // Mostrar la alerta de Bootstrap con un mensaje de error
        showBootstrapAlert(
          "Error al añadir detalle de compra: " + responseData.message,
          "danger"
        );
      }
    },
    error: function (xhr, status, error) {
      // Manejar errores de la llamada AJAX
      console.error("Error en la llamada AJAX:", status, error);
      // Mostrar la alerta de Bootstrap con un mensaje de error
      showBootstrapAlert("Error en la llamada AJAX", "danger");
    },
  });
}
// Función para cargar detalles de compra por ID de ticket
function loadDetallesCompra(idTicket) {
  $.ajax({
    type: "POST",
    url: "../controller/getDetallesCompraController.php",
    data: {
      idticket: idTicket,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        // Limpiar el cuerpo de la tabla antes de agregar nuevas filas
        $("#detallesCompraBody").empty();

        // Iterar sobre los detalles y construir filas de la tabla
        responseData.detalles.forEach(function (detalle) {
          var newRow =
            "<tr>" +
            "<td>" +
            detalle.id_detallecompra +
            "</td>" +
            "<td>" +
            detalle.nombre +
            "</td>" +
            "<td>" +
            detalle.cantidad +
            "</td>" +
            "<td>" +
            detalle.precio +
            "</td>" +
            "<td>" +
            detalle.subtotal +
            "</td>" +
            "<td>" +
            "<div class='btn-group'>" +
            "<button class='btn btn-warning btn-edit' data-id='" +
            detalle.id_detallecompra +
            "'>Editar</button>" +
            "<button class='btn btn-danger btn-delete' data-id='" +
            detalle.id_detallecompra +
            "'>Eliminar</button>" +
            "</div>" +
            "</td>" +
            "</tr>";

          $("#detallesCompraBody").append(newRow);
        });

        // Actualizar el total y mostrar la tabla

        $("#total").val(responseData.total);
        $("#total").show();
        function formatearConSeparadores(numero) {
          return numero.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        $("#totalContainer").text(
          "El total es: " + formatearConSeparadores(responseData.total)
        );
      } else {
        // Manejar el caso en que no haya detalles para el ID del ticket
        console.log(
          "No se encontraron detalles de compra para el ID del ticket: " +
            idTicket
        );
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la llamada AJAX:", status, error);
      showBootstrapAlert("Error en la llamada AJAX", "danger");
    },
  });
}

// Manejador de eventos para el botón "Editar"
$(document).on("click", ".btn-edit", function () {
  var detalleId = $(this).data("id");

  // Cargar datos del detalle de compra en el modal
  loadEditData(detalleId);

  // Abre el modal
  $("#editModal").modal("show");
});

// Función para cargar datos del detalle de compra en el modal
function loadEditData(detalleId) {
  // Puedes hacer una solicitud AJAX para obtener los datos del detalle de compra
  $.ajax({
    type: "POST",
    url: "../controller/getDetalleCompraById.php",
    data: {
      id_detallecompra: detalleId,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        // Lógica para cargar los datos en el modal
        // Por ejemplo, actualizar los campos del formulario en el modal
        $("#modalDetalleId").val(responseData.detalle.id_detallecompra);
        $("#modalCantidad").val(responseData.detalle.cantidad);
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
function saveEdit() {
  // Obtener datos editados desde el modal
  var detalleId = $("#modalDetalleId").val();
  var nuevaCantidad = $("#modalCantidad").val();

  // Puedes hacer una solicitud AJAX para enviar los datos editados al servidor
  $.ajax({
    type: "POST",
    url: "../controller/editDetalleCompra.php",
    data: {
      id_detallecompra: detalleId,
      nuevaCantidad: nuevaCantidad,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        // Actualizar la cantidad en la fila de la tabla
        //updateTable(detalleId, nuevaCantidad);
        // Obtener el valor actualizado del ticket
        var $ticket = $("#idticket").val();
        // Llamar a la función de carga con el ticket correcto
        loadDetallesCompra($ticket);

        // Cierra el modal después de guardar los cambios
        $("#editModal").modal("hide");
      } else {
        console.error("Error al guardar cambios: " + responseData.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la llamada AJAX:", status, error);
    },
  });
}

//ELIMINAR
// Manejador de eventos para el botón "Eliminar"
$(document).on("click", ".btn-delete", function () {
  var detalleId = $(this).data("id");

  // Configura el manejador de clic para el botón "Eliminar" en el modal
  $("#confirmDeleteBtn")
    .unbind()
    .on("click", function () {
      // Llama a la función para eliminar el detalle de compra
      deleteDetalleCompra(detalleId);

      // Cierra el modal después de hacer clic en "Eliminar"
      $("#confirmDeleteModal").modal("hide");
    });

  // Abre el modal de confirmación
  $("#confirmDeleteModal").modal("show");
});

// Función para eliminar el detalle de compra
function deleteDetalleCompra(detalleId) {
  $.ajax({
    type: "POST",
    url: "../controller/deleteDetalleCompra.php",
    data: {
      id_detallecompra: detalleId,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        // Obtener el valor actualizado del ticket
        var $ticket = $("#idticket").val();
        // Llamar a la función de carga con el ticket correcto
        loadDetallesCompra($ticket);

        // Mostrar la alerta de Bootstrap
        showBootstrapAlert(
          "Detalle de compra eliminado correctamente",
          "success"
        );
      } else {
        console.error(
          "Error al eliminar detalle de compra: " + responseData.message
        );
        // Mostrar la alerta de Bootstrap con un mensaje de error
        showBootstrapAlert(
          "Error al eliminar detalle de compra: " + responseData.message,
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

$(document).ready(function () {
  function limpiarCampoDecimal(event) {
    var currentValue = event.target.value;
    var dotCount = currentValue.split(".").length - 1;

    // Permitir solo números y hasta un punto decimal
    if (dotCount <= 1) {
      var cleanedValue = currentValue.replace(/[^\d.]/g, "");

      // Eliminar puntos adicionales después del primer punto
      cleanedValue = cleanedValue.replace(/\.(?=[^.]*\.)/g, "");

      // Actualizar el valor del campo
      event.target.value = cleanedValue;
    } else {
      // Si hay más de un punto, eliminar el último
      event.target.value = currentValue.slice(0, -1);
    }
  }

  // Agregar un evento de escucha para el cambio en el campo de cantidad
  $("#cantidad").on("input", limpiarCampoDecimal);

  // Agregar un evento de escucha para el caso en que se pega texto en el campo
  $("#cantidad").on("paste", function (event) {
    // Esperar un breve momento antes de procesar el contenido pegado
    setTimeout(function () {
      limpiarCampoDecimal(event);
    }, 0);
  });
});

