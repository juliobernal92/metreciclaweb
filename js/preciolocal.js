$(document).ready(function () {
  // Agrega esta función para cargar las opciones del select al cargar la página
  cargarLocales();

  // Agrega un listener al cambio del select
  $("#idlocalSelect").change(function () {
    cargarDetallesLocal();
  });
});

function cargarLocales() {
  // Realizar la llamada AJAX para obtener las opciones del select
  $.ajax({
    type: "GET",
    url: "../controller/localDetailsController.php", // Ajusta la ruta según tu estructura de archivos
    dataType: "json",
    success: function (data) {
      // Llena el select con las opciones obtenidas
      var select = $("#idlocalSelect");
      select.empty();

      select.append(
        $("<option>", {
          value: "",
          text: "Selecciona un local",
        })
      );

      $.each(data, function (index, option) {
        select.append(
          $("<option>", {
            value: option.id_localventa,
            text: option.nombre,
          })
        );
      });
      cargarChatarraSinPrecioVenta();
    },
    error: function (error) {
      console.error("Error al cargar opciones de Local:", error);
    },
  });
}

function cargarDetallesLocal() {
  // Obtener el ID del local seleccionado
  var idLocal = $("#idlocalSelect").val();

  // Realizar la llamada AJAX para obtener los detalles de los precios del local seleccionado
  $.ajax({
    type: "GET",
    url: "../controller/getpreciolocalcontroller.php", // Ajusta la ruta según tu estructura de archivos
    data: { id_local: idLocal }, // Envía el ID del local seleccionado al servidor
    dataType: "json",
    success: function (data) {
      // Limpiar la tabla antes de agregar los nuevos datos
      $("#tablaPreciolocal tbody").empty();

      // Llena la tabla con los detalles de los precios obtenidos
      $.each(data, function (index, detalle) {
        var row =
          "<tr>" +
          "<td>" +
          (index + 1) +
          "</td>" +
          "<td>" +
          detalle.nombre +
          "</td>" +
          "<td>" +
          detalle.precio +
          "</td>" +
          "<td>" +
          "<button class='btn btn-primary btn-edit' data-id='" +
          detalle.id_precioventa +
          "'>Editar</button> | <button class='btn btn-danger btn-delete' data-id='" +
          detalle.id_precioventa +
          "'>Eliminar</button>" +
          "</td" +
          "</tr>";
        $("#tablaPreciolocal tbody").append(row);
      });
    },
    error: function (error) {
      console.error("Error al cargar detalles de Local:", error);
    },
  });
}

function cargarChatarraSinPrecioVenta() {
  // Obtener el ID del local seleccionado
  var idLocal = $("#idlocalSelect").val();

  // Realizar la llamada AJAX para obtener las chatarras sin precio de venta asociado
  $.ajax({
    type: "GET",
    url: "../controller/getChatarraSinPrecioVenta.php",
    data: {
      id_local: idLocal,
    },
    dataType: "json",
    success: function (data) {
      // Limpiar el select de chatarra
      $("#idChatarraSelect").empty();

      // Agregar una opción por defecto
      $("#idChatarraSelect").append(
        $("<option>", {
          value: "",
          text: "Selecciona una chatarra",
        })
      );

      // Agregar las opciones de chatarra obtenidas
      $.each(data, function (index, chatarra) {
        $("#idChatarraSelect").append(
          $("<option>", {
            value: chatarra.id_chatarra,
            text: chatarra.nombre,
          })
        );
      });
    },
    error: function (error) {
      console.error(
        "Error al cargar las chatarras sin precio de venta:",
        error
      );
    },
  });
}

// Agrega un listener al cambio del select de locales
$("#idlocalSelect").change(function () {
  cargarDetallesLocal();
  cargarChatarraSinPrecioVenta(); // Llama a cargarChatarraSinPrecioVenta() después de seleccionar un local
});

function addPrecioLocal() {
  // Obtener el ID del local seleccionado
  var idLocal = $("#idlocalSelect").val();
  // Obtener el ID de la chatarra seleccionada
  var idChatarra = $("#idChatarraSelect").val();
  // Obtener el precio
  var precio = $("#precio").val();

  $.ajax({
    type: "POST",
    url: "../controller/preciolocalController.php", // Ajusta la ruta según tu estructura de archivos
    data: {
      id_local: idLocal,
      id_chatarra: idChatarra,
      precio: precio,
      ajax: 1,
    },
    dataType: "json",
    success: function (data) {
      // Limpiar los campos
      $("#precio").val("");
      cargarDetallesLocal();
      cargarChatarraSinPrecioVenta(); // Llama a cargarChatarraSinPrecioVenta() después de agregar un precio
      showBootstrapAlert("Chatarra Añadida correctamente", "success");
    },
    error: function (error) {
      console.error("Error al agregar precio:", error);
      showBootstrapAlert("Error al añadir chatarra", "danger");
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
  $("#alertContainer").html(alertHtml); // Corregir el selector aquí

  // Ocultar la alerta después de 5 segundos
  setTimeout(function () {
    $(".alert").alert("close"); // Intenta cerrar la alerta usando el método de Bootstrap
  }, 5000);

  // Ocultar la alerta después de 5 segundos usando vanilla JavaScript como alternativa
  setTimeout(function () {
    document.querySelector(".alert").style.display = "none";
  }, 5000);
}

// Manejador de eventos para el botón "Editar"
$(document).on("click", ".btn-edit", function () {
  var precioId = $(this).data("id");
  $("#idprecio").val(precioId);

  loadEditData(precioId);

  // Abre el modal
  $("#editModal").modal("show");
});

function loadEditData(precioId) {
  $.ajax({
    type: "POST",
    url: "../controller/getpreciobyid.php",
    data: {
      id_precioventa: precioId,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        $("#modalPrecioId").val(responseData.precio.id_precioventa);
        $("#modalNombre").val(responseData.precio.nombre);
        $("#modalPrecio").val(responseData.precio.precioventa);
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
    var precioId = $("#idprecio").val();
    guardaredit(precioId);

}

function guardaredit (precioId){
  var nuevoPrecio = $("#modalPrecio").val();
  $.ajax({
    type: "POST",
    url: "../controller/editpreciolocalcontroller.php",
    data: {
      id_precioventa: precioId,
      nuevoPrecio: nuevoPrecio,
      ajax: 1,
    },
    dataType: "json",
    success: function (responseData) {
      if (responseData.success) {
        $("#editModal").modal("hide");
        cargarDetallesLocal();
        showBootstrapAlert("Precio editado correctamente", "success");

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



//ELIMINAR
// Manejador de eventos para el botón "Eliminar"
$(document).on("click", ".btn-delete", function () {
    var precioId = $(this).data("id");
  
    // Configura el manejador de clic para el botón "Eliminar" en el modal
    $("#confirmDeleteBtn")
      .unbind()
      .on("click", function () {
        // Llama a la función para eliminar la chatarra
        deletePrecio(precioId);
  
        // Cierra el modal después de hacer clic en "Eliminar"
        $("#confirmDeleteModal").modal("hide");
      });
  
    // Abre el modal de confirmación
    $("#confirmDeleteModal").modal("show");
  });




  // Función para eliminar la chatarra
function deletePrecio(precioId) {
    $.ajax({
      type: "POST",
      url: "../controller/deleteprecio.php",
      data: {
        id_precioventa: precioId,
        ajax: 1,
      },
      dataType: "json",
      success: function (responseData) {
        if (responseData.success) {
          cargarDetallesLocal();
  
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