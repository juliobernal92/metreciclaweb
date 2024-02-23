// Agrega esta función para cargar las opciones del select al cargar la página
$(document).ready(function () {
  loadChatarraOptions();

  // Agrega un listener al cambio del select
  $("#idChatarraSelect").change(function () {
    loadChatarraDetails();
    loadPrecioVenta();
  });
});

function loadChatarraOptions() {
  // Realizar la llamada AJAX para obtener las opciones del select
  $.ajax({
    type: "GET",
    url: "../controller/getchatarraventascontroller.php",
    dataType: "json",
    data: { action: "getOptions" },
    success: function (data) {
      // Llena el select con las opciones obtenidas
      var select = $("#idChatarraSelect");
      select.empty();

      select.append(
        $("<option>", {
          value: "",
          text: "Selecciona una chatarra",
        })
      );

      $.each(data, function (index, option) {
        select.append(
          $("<option>", {
            value: option.id_chatarra,
            text: option.nombre,
          })
        );
      });
    },
    error: function (error) {
      console.error("Error al cargar opciones de Chatarra:", error);
    },
  });
}

function loadChatarraDetails() {
  // Obtener el ID seleccionado
  var selectedChatarraId = $("#idChatarraSelect").val();

  // Realizar la llamada AJAX para obtener detalles
  $.ajax({
    type: "GET",
    url: "../controller/getchatarraventascontroller.php",
    data: { action: "getDetails", idChatarra: selectedChatarraId },
    dataType: "json",
    success: function (responseData) {
      // Actualizar los campos con el ID y precio obtenidos
      $("#idChatarra").val(responseData.id_chatarra);
      $("#preciocompra").val(responseData.precio);
    },
    error: function (error) {
      console.error("Error al cargar detalles de Chatarra:", error);
    },
  });
}

function loadPrecioVenta() {
  // Obtener el ID seleccionado
  var selectedChatarraId = $("#idChatarraSelect").val();
  // Verificar si se ha seleccionado un local
  var selectedLocalId = $("#idlocalSelect").val();
  console.log("local id: ", selectedLocalId);
  if (!selectedLocalId) {
    limpiarCamposChatarra();
    showAlert("Debe seleccionar primero un local.");

    // Limpiar campos y volver al inicio del select de chatarra

    return;
  }
  // Realizar la llamada AJAX para obtener detalles
  $.ajax({
    type: "GET",
    url: "../controller/getprecioventa.php",
    data: { idChatarra: selectedChatarraId },
    dataType: "json",
    success: function (responseData) {
      if (responseData.error) {
        // Si hay un error, mostrar mensaje de alerta
        showAlert(
          "Chatarra '" +
            $("#idChatarraSelect option:selected").text() +
            "' no encontrada en el local seleccionado."
        );
        // Borrar los campos de los datos
        $("#precioventa").val("");
        $("#preciocompra").val("");

        // Puedes agregar más campos si es necesario

        // Volver al inicio del select
        $("#idChatarraSelect").val("");
      } else {
        // Actualizar el campo con el precio de venta obtenido
        $("#precioventa").val(responseData.precioventa);
        // Actualizar el campo oculto con el id_precioventa obtenido
        $("#idprecioventa").val(responseData.id_precioventa);
      }
    },
    error: function (error) {
      console.error("Error al cargar detalles de Chatarra:", error);
    },
  });
}

function showAlert(message) {
  // Agregar mensaje de alerta a un contenedor en la vista
  $("#alertContainer").html(`
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `);

  // Ocultar la alerta después de 5 segundos
  setTimeout(function () {
    $(".alert").alert("close"); // Intenta cerrar la alerta usando el método de Bootstrap
  }, 3000);

  // Ocultar la alerta después de 5 segundos usando vanilla JavaScript como alternativa
  setTimeout(function () {
    document.querySelector(".alert").style.display = "none";
  }, 3000);
}

function limpiarCamposChatarra() {
  console.log("Limpiando campos");
  $("#idChatarra").val("");
  $("#precioventa").val("");
  $("#idChatarraSelect").val("");
  $("#preciocompra").val("");
}


///-----------------------------------------------------------------------------------
