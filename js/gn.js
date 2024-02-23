$(document).ready(function() {
    // Obtener el ID del empleado (puedes cambiar esto según tu lógica)
    var idEmpleado = $('#id_empleado').val();

    // Realizar la solicitud AJAX al controlador
    $.ajax({
        type: 'GET',
        url: '/metreciclaweb/controller/getName.php',
        data: {
            id_empleado: idEmpleado
        },
        dataType: 'json',
        success: function(response) {
            if (response.nombre) {
                // Actualizar el contenido del contenedor del mensaje de bienvenida
                $('#bienvenidaContainer').html('Bienvenido ' + response.nombre);
            } else {
                console.error('Error al obtener el nombre del empleado:', response.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud AJAX:', status, error);
        }
    });
});