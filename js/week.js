document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById('myChart').getContext('2d');

    // Realiza la solicitud AJAX para obtener los datos del servidor
    $.ajax({
        type: 'GET',
        url: 'controller/weekchartcontroller.php', // Asegúrate de ajustar la ruta correcta
        dataType: 'json',
        success: function (dataFromServer) {
            // Ordena los datos por MontoTotal de forma descendente
            dataFromServer.sort((a, b) => b.MontoTotal - a.MontoTotal);

            // Extrae las etiquetas y datos del arreglo ordenado
            var labels = dataFromServer.map(entry => entry.Chatarra);
            var kilosComprados = dataFromServer.map(entry => entry.KilosComprados);
            var montoTotal = dataFromServer.map(entry => entry.MontoTotal);

            // Crea el gráfico de barras
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kilos Comprados',
                        data: kilosComprados,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Monto Total',
                        data: montoTotal,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        datalabels: {
                            anchor: 'end',
                            align: 'end',
                            formatter: function (value, context) {
                                // Muestra tanto los kilos como el monto total
                                return `Kg: ${kilosComprados[context.dataIndex].toFixed(2)} - Monto: $${montoTotal[context.dataIndex].toFixed(2)}`;
                            },
                        }
                    }
                }
            });
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', status, error);
        }
    });
});
