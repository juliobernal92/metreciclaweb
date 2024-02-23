function addTotal() {
  var idticket = $("#idticket").val();
  var total = $("#total").val();
  $.ajax({
    type: "POST",
    url: "../controller/addtotalController.php",
    data: {
      idticket: idticket,
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
    },
  });
}
function reloadPag() {
  location.reload();
}

function generarTicket() {
  const fecha = obtenerFechaHoraActual();
  const pdfWidth = 80; // Ancho personalizado en puntos (ajusta según las especificaciones de tu impresora térmica)
  const pdf = new window.jspdf.jsPDF({
    unit: "mm",
    format: [pdfWidth, 180], // Establecer solo el ancho, la altura puede ser la que desees
  });
  const proveedor = $("#nombre").val();
  const empleado = $("#bienvenidaContainer").text().replace("Bienvenido ", ""); // Obtener el nombre del empleado

  const logo = new Image();
  logo.src = "../img/logo.png"; // Reemplaza con la ruta correcta de tu imagen

  const logoWidth = 20; // Ajusta el ancho de la imagen según tu preferencia
  const logoX = (pdfWidth - logoWidth) / 2; // Calcular la posición central en X

  pdf.addImage(logo, "PNG", logoX, 5, logoWidth, 20); // Centrar la imagen horizontalmente
  pdf.setFontSize(8);
  pdf.setFont("helvetica", "bold");
  pdf.text(
    "MET RECICLA",
    pdf.internal.pageSize.getWidth() / 2,
    30,
    null,
    "center"
  );
  pdf.setFont("helvetica", "normal");
  pdf.text("Teléfono: (0984) 749-327", 5, 35);
  pdf.text(`Fecha: ${fecha}`, 5, 40);
  pdf.text(`Proveedor: ${proveedor}`, 5, 45);
  pdf.text(`Encargado: ${empleado}`, 5, 50);

  pdf.setFillColor(200, 200, 200);
  pdf.setTextColor(0, 0, 0);
  pdf.setFont("helvetica", "bold");

  pdf.rect(5, 55, pdfWidth - 10, 5, "F");

  pdf.setFontSize(8);

  pdf.text("CANT", 5, 60);
  pdf.text("DESC", 20, 60);
  pdf.text("PREC", 45, 60);
  pdf.text("SUBT", 65, 60);

  pdf.setTextColor(0, 0, 0);
  pdf.setFont("helvetica", "normal");

  let y = 65;

  $("#tablaDetallesCompraContainer table tbody tr").each(function (index) {
    const cantidad = $(this).find("td:eq(0)").text();
    const descripcion = $(this).find("td:eq(1)").text();
    const precioUnitario = parseFloat(
      $(this).find("td:eq(2)").text().replace(/,/g, "")
    );
    const subtotal = parseFloat(
      $(this).find("td:eq(3)").text().replace(/,/g, "")
    );

    pdf.text(`${cantidad}`, 5, y + (index * 5));
    pdf.text(`${descripcion}`, 20, y + (index * 5));
    pdf.text(`${precioUnitario.toLocaleString()}`, 45, y + (index * 5));
    pdf.text(`${subtotal.toLocaleString()}`, 65, y + (index * 5));
  });

  pdf.setFontSize(10);

  const total = parseFloat($("#total").val().replace(/,/g, ""));
  pdf.text("TOTAL:", 40, y + 20);
  pdf.text(`${total.toLocaleString()}`, 60, y + 20);

  var idticket = $("#idticket").val();

  const nombreArchivo = `ticket_${proveedor}_${idticket}.pdf`;

  pdf.save(nombreArchivo);
}


function obtenerFechaActual() {
  const now = new Date();
  const dia = now.getDate().toString().padStart(2, "0");
  const mes = (now.getMonth() + 1).toString().padStart(2, "0");
  const año = now.getFullYear();
  return `${dia}-${mes}-${año}`;
}

function obtenerFechaHoraActual() {
  const ahora = new Date();
  const dia = ahora.getDate().toString().padStart(2, "0");
  const mes = (ahora.getMonth() + 1).toString().padStart(2, "0");
  const año = ahora.getFullYear();
  const horas = ahora.getHours().toString().padStart(2, "0");
  const minutos = ahora.getMinutes().toString().padStart(2, "0");
  const segundos = ahora.getSeconds().toString().padStart(2, "0");

  return `${dia}-${mes}-${año} ${horas}:${minutos}:${segundos}`;
}


function cerrarModalEdit(){
    $("#editModal").modal("hide");
  }