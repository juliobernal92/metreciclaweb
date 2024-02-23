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
  }, 1000);
}

function formatDateToYMD(dateString) {
  var parts = dateString.split("/");
  if (parts.length === 3) {
    return parts[2] + "-" + parts[1] + "-" + parts[0];
  }
  return dateString; // Devolver el mismo valor si no se puede formatear
}

function addGastoForm() {
  var concepto = $("#concepto").val();
  var monto = $("#monto").val();
  var fechaf = $("#fecha").val();
  var fecha = formatDateToYMD(fechaf);
  var empleado = $("#idempleado").val();
  var token_csrf = $('[name="token_csrf"]').val();
  console.log("Fecha pasando: ", fecha);

  // Validar nombre y apellido solo contienen letras o espacios
  if (!/^[a-zA-Z\s]+$/.test(concepto)) {
    showBootstrapAlert("Por favor, ingrese solo letras o espacios.", "warning");
    return;
  }

  // Validar teléfono solo contiene números
  if (!/^\d+$/.test(monto)) {
    showBootstrapAlert("Por favor, ingrese solo números.", "warning");
    return;
  }

  // Realizar la solicitud AJAX al backend
  $.ajax({
    type: "POST",
    url: "/mr/controller/creargastoController.php",
    data: {
      concepto: concepto,
      monto: monto,
      fecha: fecha,
      idempleado: empleado,
      token_csrf: token_csrf,
    },
    dataType: "json",
    success: function (response) {
      console.log(response);
      showBootstrapAlert("Gasto registrado con éxito.", "success");
      setTimeout(function () {
        window.location.href = "/mr/vistas/gastos.php";
      }, 5000);
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", status, error);
      showBootstrapAlert("Error en la solicitud AJAX", "danger");
    },
  });
}
