function comprobarTicket() {
  var idticketventa = $("#idticketventa").val();
  if (idticketventa === "" || idticketventa === undefined) {
    addTicketVenta();
  } else {
    addDetalleVenta();
  }
}

function addTicketVenta() {
  var fecha = $("#fecha").val();
  var idempleado = $("#idempleado").val();
  $.ajax({
    type: "POST",
    url: "../controller/ticketventacontroller.php",
    data: {
      fecha: fecha,
      idempleado: idempleado,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        $("#idticketventa").val(responseData.idticketventa);
        console.log("TICKET VENTA: ", responseData.idticketventa);
        addDetalleVenta();
        showBootstrapAlert("Ticket de venta añadido correctamente", "success");
      } else {
        console.error(
          "Error al añadir ticket de venta: " + responseData.message
        );
        showBootstrapAlert(
          "Error al añadir ticket de venta: " + responseData.message,
          "danger"
        );
      }
    },
  });
}

function addDetalleVenta() {
  var idprecioventa = $("#idprecioventa").val();
  var cantidad = $("#cantidad").val();
  var precioVenta = $("#precioventa").val();
  var subtotal = cantidad * precioVenta;
  var idticketventa = $("#idticketventa").val();
  $.ajax({
    type: "POST",
    url: "../controller/detallesventacontroller.php",
    data: {
      idprecioventa: idprecioventa,
      cantidad: cantidad,
      subtotal: subtotal,
      idticketventa: idticketventa,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        $("#cantidad").val("");
        $("#idChatarraSelect").focus();
        actualizarTabla(idticketventa);
        limpiarCamposChatarra();
        
        showBootstrapAlert("Chatarra añadida correctamente", "success");
      } else {
        console.error("Error al añadir la chatarra: " + responseData.message);
        showBootstrapAlert(
          "Error al añadir la chatarra: " + responseData.message,
          "danger"
        );
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la llamada AJAX:", status, error);
      showBootstrapAlert("Error en la llamada AJAX", "danger");
    },
  });
}

// Agrega una función para cargar detalles de venta (similar a loadDetallesCompra)
function loadDetallesVenta() {
  // Implementa la lógica para cargar los detalles de venta aquí
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

function limpiarCamposChatarra() {
  console.log("Limpiando campos");
  $("#idChatarra").val("");
  $("#precioventa").val("");
  $("#idChatarraSelect").val("");
  $("#preciocompra").val("");
  $("#cantidad").val("");
  $("#cantidad").focus();
}

function actualizarTabla(idticketventa) {
  $.ajax({
    type: "POST",
    url: "../controller/getdetallesventacontroller.php",
    data: {
      idticketventa: idticketventa,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      console.log("Respuesta del servidor:", responseData); 
      if (responseData.success) {
        $("#detallesVentaBody").empty();

        responseData.detalles.forEach(function (detalle) {
          var newRow =
            "<tr>" +
            "<td>" +
            detalle.id_detallesventa +
            "</td>" +
            "<td>" +
            detalle.nombre_chatarra +
            "</td>" +
            "<td>" +
            detalle.cantidad +
            "</td>" +
            "<td>" +
            detalle.precioventa +
            "</td>" +
            "<td>" +
            detalle.subtotal +
            "</td>" +
            "<td>" +
            "<div class='btn-group'>" +
            "<button class='btn btn-warning btn-edit' data-id='" +
            detalle.id_detallesventa +
            "'>Editar</button>" +
            "<button class='btn btn-danger btn-delete' data-id='" +
            detalle.id_detallesventa +
            "'>Eliminar</button>" +
            "</div>" +
            "</td>" +
            "</tr>";
          $("#detallesVentaBody").append(newRow);

          // Actualizar el total y mostrar la tabla

          $("#total").val(responseData.total);
          $("#total").show();
          function formatearConSeparadores(numero) {
            return numero.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          }

          $("#totalContainer").text(
            "El total es: " + formatearConSeparadores(responseData.total)
          );
        });
      } else {
        // Manejar el caso en que no haya detalles para el ID del ticket
        console.log(
          "No se encontraron detalles de compra para el ID del ticket: " +
            idticketventa
        );
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la llamada AJAX:", status, error);
      showBootstrapAlert("Error en la llamada AJAX", "danger", status, error);
    },
  });
}



// Manejador de eventos para el botón "Editar"
$(document).on("click", ".btn-edit", function () {
  var detalleId = $(this).data("id");
  console.log("ID QUE ESTA PASANDO; ", detalleId);

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
    url: "../controller/getDetalleVentaById.php",
    data: {
      id_detallesventa: detalleId,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        // Lógica para cargar los datos en el modal
        // Por ejemplo, actualizar los campos del formulario en el modal
        $("#modalDetalleId").val(responseData.detalle.id_detallesventa);
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

  console.log("ID en modal: ",detalleId);
  console.log("Nueva cantidad en modal: ", nuevaCantidad);

  // Puedes hacer una solicitud AJAX para enviar los datos editados al servidor
  $.ajax({
    type: "POST",
    url: "../controller/editDetalleVenta.php",
    data: {
      id_detallesventa: detalleId,
      nuevaCantidad: nuevaCantidad,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      console.log("Respuesta del servidor:", responseData);
      if (responseData.success) {
        var ticketventa = $("#idticketventa").val();
        actualizarTabla(ticketventa);

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
      deleteDetalleVenta(detalleId);

      // Cierra el modal después de hacer clic en "Eliminar"
      $("#confirmDeleteModal").modal("hide");
    });

  // Abre el modal de confirmación
  $("#confirmDeleteModal").modal("show");
});


// Función para eliminar el detalle de compra
function deleteDetalleVenta(detalleId) {
  $.ajax({
    type: "POST",
    url: "../controller/deleteDetalleVenta.php",
    data: {
      id_detallesventa: detalleId,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        // Obtener el valor actualizado del ticket
        var ticket = $("#idticketventa").val();
        // Llamar a la función de carga con el ticket correcto
        actualizarTabla(ticket);
limpiarCamposChatarra();
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



function cerrarModalEdit(){
  $("#editModal").modal("hide");
}