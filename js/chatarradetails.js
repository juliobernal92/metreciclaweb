// Agrega esta función para cargar las opciones del select al cargar la página
$(document).ready(function () {
    loadChatarraOptions();

    // Agrega un listener al cambio del select
    $("#idChatarraSelect").change(function () {
        loadChatarraDetails();
    });
});

function loadChatarraOptions() {
    // Realizar la llamada AJAX para obtener las opciones del select
    $.ajax({
        type: "GET",
        url: "../controller/chatarraDetailsController.php",
        dataType: "json",
        data: { action: "getOptions" },
        success: function (data) {
            // Llena el select con las opciones obtenidas
            var select = $("#idChatarraSelect");
            select.empty();

            select.append($('<option>', {
                value: '',
                text: 'Selecciona una chatarra'
            }));

            $.each(data, function (index, option) {
                select.append($('<option>', {
                    value: option.id_chatarra,
                    text: option.nombre
                }));
            });
        },
        error: function (error) {
            console.error("Error al cargar opciones de Chatarra:", error);
        }
    });
}

function loadChatarraDetails() {
    // Obtener el ID seleccionado
    var selectedChatarraId = $("#idChatarraSelect").val();

    // Realizar la llamada AJAX para obtener detalles
    $.ajax({
        type: "GET",
        url: "../controller/chatarraDetailsController.php",
        data: { action: "getDetails", idChatarra: selectedChatarraId },
        dataType: "json",
        success: function (responseData) {
            // Actualizar los campos con el ID y precio obtenidos
            $("#idChatarra").val(responseData.id_chatarra);
            $("#precio").val(responseData.precio);
        },
        error: function (error) {
            console.error("Error al cargar detalles de Chatarra:", error);
        }
    });
}
