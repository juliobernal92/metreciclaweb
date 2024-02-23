// gl.js
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
        url: "../controller/localDetailsController.php",  // Ajusta la ruta según tu estructura de archivos
        dataType: "json",
        success: function (data) {
            // Llena el select con las opciones obtenidas
            var select = $("#idlocalSelect");
            select.empty();

            select.append($('<option>', {
                value: '',
                text: 'Selecciona un local'
            }));

            $.each(data, function (index, option) {
                select.append($('<option>', {
                    value: option.id_localventa,
                    text: option.nombre
                }));
            });
        },
        error: function (error) {
            console.error("Error al cargar opciones de Local:", error);
        }
    });
}

function cargarDetallesLocal() {
    // Obtener el ID seleccionado
    var selectedLocalId = $("#idlocalSelect").val();

    // Actualizar el campo oculto con el valor seleccionado
    $("#idlocal").val(selectedLocalId);
    
}
