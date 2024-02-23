function showBootstrapAlert(message, type) {
    // Agregar la alerta al DOM
    var alertHtml =
        '<div class="alert alert-' +
        type +
        ' alert-dismissible fade show" role="alert">' +
        message +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
        '</div>';
    $("#alertContainer").html(alertHtml);

    // Ocultar la alerta después de 5 segundos
    setTimeout(function () {
        $(".alert").fadeOut();
    }, 5000);    
}

function addEmpleado() {
    var nombre = $('#nombre').val();
    var apellido = $('#apellido').val();
    var telefono = $('#telefono').val();
    var direccion = $('#direccion').val();
    var cedula = $('#cedula').val();
    var contraseña = $('#contraseña').val();
    var token_csrf = $('[name="token_csrf"]').val();

    // Validar nombre y apellido solo contienen letras
    if (!/^[a-zA-Z]+$/.test(nombre) || !/^[a-zA-Z]+$/.test(apellido)) {
        showBootstrapAlert('Por favor, ingrese solo letras en Nombre y Apellido.', 'warning');
        return;
    }

    // Validar dirección (letras y números)
    if (!/^[a-zA-Z0-9\s]+$/.test(direccion)) {
        showBootstrapAlert('Por favor, ingrese letras y números en Dirección.', 'warning');
        return;
    }

    // Validar contraseña (mínimo 8 caracteres)
    if (contraseña.length < 8) {
        showBootstrapAlert('La contraseña debe tener al menos 8 caracteres.', 'warning');
        return;
    }

    // Validar teléfono solo contiene números
    if (!/^\d+$/.test(telefono)) {
        showBootstrapAlert('Por favor, ingrese solo números en Teléfono.', 'warning');
        return;
    }

    // Realizar la solicitud AJAX al backend
    $.ajax({
        type: 'POST',
        url: '/mr/controller/crearempleadoController.php',
        data: {
            nombre: nombre,
            apellido: apellido,
            telefono: telefono,
            direccion: direccion,
            cedula: cedula,
            contraseña: contraseña,
            token_csrf: token_csrf
        },
        dataType: 'json',
        success: function (response) {
            console.log(response);
            showBootstrapAlert('Empleado creado con éxito.', 'success');
            setTimeout(function () {
                window.location.href = '/mr/vistas/empleados.php';
            }, 1000);
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', status, error);
            showBootstrapAlert('Error en la solicitud AJAX', 'danger');
        }
    });
}
