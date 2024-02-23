function addTotalVenta(){
    var idticketventa = $("#idticketventa").val();
    var total = $("#total").val();
    $.ajax({
        type: "POST",
        url: "../controller/addtotalVentaController.php",
        data: {
            idticketventa: idticketventa,
            total: total,
            ajax: 1,
        },
        dataType: "json",
        success: function (responseData) {
            console.log("OK");
            generarTicket();
            reloadPag();
        },
        error: function (xhr, status, error) {
            console.error("Error en la llamada AJAX:", status, error);
        }
    });
}


function reloadPag(){
    location.reload();
}

function generarTicket() {
    const selectlocal = $("#idlocalSelect");
    const localselect = selectlocal.find("option:selected");
    const nombrelocal = localselect.text();

    const fecha = obtenerFechaHoraActual();
    const pdf = new window.jspdf.jsPDF({
        unit: 'mm',
        format: 'a4' // Establecer el formato como A4
    });
    const proveedor = $("#nombre").val();
    const empleado = $('#bienvenidaContainer').text().replace('Bienvenido ', ''); // Obtener el nombre del empleado

    const logo = new Image();
    logo.src = '../img/logo.png'; // Reemplaza con la ruta correcta de tu imagen

    const logoWidth = 30; // Ajusta el ancho de la imagen según tu preferencia
    const logoX = (pdf.internal.pageSize.getWidth() - logoWidth) / 2; // Calcular la posición central en X

    pdf.addImage(logo, 'PNG', logoX, 10, logoWidth, 20); // Centrar la imagen horizontalmente
    pdf.setFontSize(10);
    pdf.setFont("helvetica", "bold");

    // Establecer la posición y el formato del texto de la línea "----------VENTA------------"
    pdf.text("MET RECICLA", pdf.internal.pageSize.getWidth() / 2, 40, null, 'center');
    pdf.text("----------VENTA------------", pdf.internal.pageSize.getWidth() / 2, 45, null, 'center');

    // Configurar el resto de la información del encabezado
    pdf.setFont("helvetica", "normal");
    pdf.text("Teléfono: (0984) 749-327", 10, 50);
    pdf.text(`Fecha: ${fecha}`, 10, 55);
    pdf.text(`Local: ${nombrelocal}`, 10, 60);
    pdf.text(`Encargado: ${empleado}`, 10, 65);

    pdf.setFillColor(0, 0, 0);
    pdf.setTextColor(255, 255, 255);
    pdf.setFont("helvetica", "bold");

    pdf.rect(10, 70, pdf.internal.pageSize.getWidth() - 20, 10, "F");

    pdf.setFontSize(8);

    pdf.text("Descripción", 15, 75); // Descripción primero
    pdf.text("Cantidad", 60, 75); // Cantidad segundo
    pdf.text("Precio", 105, 75); // Precio tercero
    pdf.text("Subtotal", 150, 75); // Subtotal cuarto

    pdf.setTextColor(0, 0, 0);
    pdf.setFont("helvetica", "normal");

    let y = 85;

    $('#tablaDetallesVentaContainer table tbody tr').each(function () {
        const cantidad = $(this).find('td:eq(2)').text();
        const descripcion = $(this).find('td:eq(1)').text();
        const precioUnitario = parseFloat($(this).find('td:eq(3)').text().replace(/,/g, ''));
        const subtotal = parseFloat($(this).find('td:eq(4)').text().replace(/,/g, ''));

        pdf.text(`${descripcion}`, 15, y); // Dibujar la descripción primero
        pdf.text(`${cantidad}`, 60, y); // Dibujar la cantidad segundo
        pdf.text(`${precioUnitario.toLocaleString()}`, 105, y); // Dibujar el precio tercero
        pdf.text(`${subtotal.toLocaleString()}`, 150, y); // Dibujar el subtotal cuarto

        y += 8;
    });

    pdf.setFontSize(10);

    const total = parseFloat($('#total').val().replace(/,/g, ''));
    pdf.text("TOTAL:", 85, y + 10);
    pdf.text(`${total.toLocaleString()}`, 135, y + 10);

    var idticket = $("#idticketventa").val();

    const nombreArchivo = `ticket_${nombrelocal}_${idticket}.pdf`;

    pdf.save(nombreArchivo);
}



function obtenerFechaActual() {
    const now = new Date();
    const dia = now.getDate().toString().padStart(2, '0');
    const mes = (now.getMonth() + 1).toString().padStart(2, '0');
    const año = now.getFullYear();
    return `${dia}-${mes}-${año}`;
}

function obtenerFechaHoraActual() {
    const ahora = new Date();
    const dia = ahora.getDate().toString().padStart(2, '0');
    const mes = (ahora.getMonth() + 1).toString().padStart(2, '0');
    const año = ahora.getFullYear();
    const horas = ahora.getHours().toString().padStart(2, '0');
    const minutos = ahora.getMinutes().toString().padStart(2, '0');
    const segundos = ahora.getSeconds().toString().padStart(2, '0');

    return `${dia}-${mes}-${año} ${horas}:${minutos}:${segundos}`;
}