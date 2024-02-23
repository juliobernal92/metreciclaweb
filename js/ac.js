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
  
  function addChatarraForm() {
    var nombre = $("#nombre").val();
    var precio = $("#precio").val();
    var token_csrf = $('[name="token_csrf"]').val();
  
    // Validar nombre y apellido solo contienen letras o espacios
    if (!/^[a-zA-Z\s]+$/.test(nombre)) {
      showBootstrapAlert(
        "Por favor, ingrese solo letras o espacios en Nombre.",
        "warning"
      );
      return;
    }
  
    // Validar teléfono solo contiene números
    if (!/^\d+$/.test(precio)) {
      showBootstrapAlert(
        "Por favor, ingrese solo números en Teléfono.",
        "warning"
      );
      return;
    }
  
    // Realizar la solicitud AJAX al backend
    $.ajax({
      type: "POST",
      url: "/mr/controller/crearchatarraController.php",
      data: {
        nombre: nombre,
        precio: precio,
        token_csrf: token_csrf,
      },
      dataType: "json",
      success: function (response) {
        console.log(response);
        showBootstrapAlert("Chatarra añadida con éxito.", "success");
        setTimeout(function () {
          window.location.href = "/mr/vistas/chatarra.php";
        }, 1000);
      },
      error: function (xhr, status, error) {
        console.error("Error en la solicitud AJAX:", status, error);
        showBootstrapAlert("Error en la solicitud AJAX", "danger");
      },
    });
  }
  