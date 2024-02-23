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

$(document).ready(function() {
    // Inicializar DataTables una vez que los datos se hayan cargado
    $("#tablaGastos").DataTable({
        language: {
            url: "../json/Spanish.json"
        }
    });
  });
  
  // En lugar de destruir y volver a inicializar la tabla, usar ajax.reload()
function cargarDatosTabla() {
    $.ajax({
        type: "GET",
        url: "../controller/getgastoscontroller.php",
        dataType: "json",
        success: function (gastos) {
            // Limpiar la tabla antes de agregar nuevos datos
            $("#tablaGastos").dataTable().fnClearTable();
  
            // Construir las filas de la tabla con los datos obtenidos
            for (var i = 0; i < gastos.length; i++) {
                var gasto = gastos[i];
                var row = [
                    (i + 1),
                    gasto.concepto,
                    gasto.monto,
                    gasto.fecha,
                    "<button class='btn btn-primary btn-edit' data-id='" + gasto.id + "'>Editar</button> | <button class='btn btn-danger btn-delete' data-id='" + gasto.id + "'>Eliminar</button>"
                ];
                $("#tablaGastos").dataTable().fnAddData(row);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", status, error);
        },
    });
  }


  function obtenerTotales() {
    $.ajax({
        type: "GET",
        url: "../controller/getgastosdiasemanames.php",
        dataType: "json",
        success: function (totales) {
            // Mostrar los totales en el div gastoshoy
            function formatearConSeparadores(numero) {
                return numero.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              }
            $("#gastoshoy").html("<h5>Gastos:</h5><p>Hoy: <b>Gs." + formatearConSeparadores(totales.total_hoy)+ "</b></p><p>Semana: <b>Gs." + formatearConSeparadores(totales.total_semana) + "</b></p><p>Mes: <b>Gs." + formatearConSeparadores(totales.total_mes) + "</b></p>");
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", status, error);
        },
    });
}