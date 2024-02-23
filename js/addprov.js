function addProveedor() {
  // Obtener los valores del formulario
  var nombre = $("#nombre").val();
  var telefono = $("#telefono").val();
  var direccion = $("#direccion").val();

  // Realizar la llamada AJAX
  $.ajax({
    type: "POST",
    url: "../controller/proveedoresController.php",
    data: {
      nombre: nombre,
      telefono: telefono,
      direccion: direccion,
      ajax: 1, // Agregar el parámetro ajax=1 para identificar la solicitud AJAX
    },
    dataType: "json", // Esperar una respuesta en formato JSON
    success: function (responseData) {
      if (responseData.success) {
        // Éxito: Actualizar el campo idvendedor con el ID del proveedor añadido
        $("#idvendedor").val(responseData.idProveedor);
        addTicket();
        // Mostrar la alerta de Bootstrap
        showBootstrapAlert("Proveedor Añadido Correctamente", "success");
      } else {
        // Error: Mostrar el mensaje de error
        console.error("Error al añadir proveedor: " + responseData.message);

        // Mostrar la alerta de Bootstrap con un mensaje de error
        showBootstrapAlert(
          "Error al añadir proveedor: " + responseData.message,
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
  }, 3000);
}


function formatDateToYMD(dateString) {
  var parts = dateString.split("/");
  if (parts.length === 3) {
    return parts[2] + "-" + parts[1] + "-" + parts[0];
  }
  return dateString; // Devolver el mismo valor si no se puede formatear
}

function addTicket() {
  // Obtener los valores del formulario
  var idvendedor = $("#idvendedor").val();
  var fecha = $("#fecha").val();
  var idempleado = $("#idempleado").val();

  // Formatear la fecha al formato "Y-m-d"
  var fechaFormatted = formatDateToYMD(fecha);

  // Realizar la llamada AJAX
  $.ajax({
    type: "POST",
    url: "../controller/ticketController.php",
    data: {
      idvendedor: idvendedor,
      fecha: fechaFormatted, // Enviar la fecha formateada
      idempleado: idempleado,
      ajax: 1, // Agregar el parámetro ajax=1 para identificar la solicitud AJAX
    },
    dataType: "json", // Esperar una respuesta en formato JSON
    success: function (responseData) {
      // Manejar la respuesta del servidor
      $("#idticket").val(responseData.idticket);
    },
  });
}

// Agregar un evento al presionar Enter en el campo ID del proveedor
$(document).ready(function () {
  // Evento cuando se presiona una tecla en el campo de ID Vendedor
  $("#idvendedor").keydown(function (e) {
    // Verificar si la tecla presionada es "Enter" (código 13)
    if (e.which === 13) {
      // Realizar la búsqueda en la base de datos
      buscarProveedor();
    }
  });
});

function buscarProveedor() {
    var idvendedor = $("#idvendedor").val();
  
    $.ajax({
      type: "POST",
      url: "../controller/getProveedor.php",
      data: {
        idvendedor: idvendedor,
        ajax: 1,
      },
      dataType: "json",
      success: function (responseData) {
        if (responseData.success) {
          // Proveedor encontrado, mostrar información
          var proveedor = responseData.proveedor;
          $("#idvendedor").val(proveedor.id_proveedor);  // Cambiar a id_proveedor
          $("#nombre").val(proveedor.nombre);
          $("#telefono").val(proveedor.telefono);
          $("#direccion").val(proveedor.direccion);
          // Eliminar la llamada a addTicket(), ya que no está definida en el código proporcionado
          // Mostrar mensaje de éxito
          addTicket();
          showBootstrapAlert("Proveedor encontrado", "success");
        } else {
          // Proveedor no encontrado, mostrar mensaje de error
          console.error("Error: " + responseData.message);
          showBootstrapAlert("Error: " + responseData.message, "danger");
        }
      },
      error: function (xhr, status, error) {
        // Manejar errores de la llamada AJAX
        console.error("Error en la llamada AJAX:", status, error);
        showBootstrapAlert("Error en la llamada AJAX", "danger");
      },
    });
  }
  