document.addEventListener('DOMContentLoaded', function() {
    google.charts.load('current', { packages: ['corechart', 'table'] });
    google.charts.setOnLoadCallback(drawDailyChart);

    function drawDailyChart() {
        fetch('/mr/controller/dailychartcontroller.php')
            .then(response => response.json())
            .then(data => {
                // Crear un objeto DataTable
                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Chatarra');
                dataTable.addColumn('number', 'Kilos');
                dataTable.addColumn('number', 'Monto');
                
                // Variables para almacenar la suma del monto total
                var totalMonto = 0;

                // Agregar filas a la DataTable
                data.forEach(entry => {
                    var kilosComprados = parseFloat(entry.KilosComprados);
                    var montoTotal = parseFloat(entry.MontoTotal);
                    dataTable.addRow([entry.Chatarra, kilosComprados, montoTotal]);

                    // Sumar al total del monto
                    totalMonto += montoTotal;
                });
    
                // Dibujar la tabla
                var table = new google.visualization.Table(document.getElementById('dailyTable'));
                table.draw(dataTable, {showRowNumber: true, width: '100%', height: '100%'});

                // Mostrar el total del monto con separadores de miles en el elemento <p>
                var formattedTotalMonto = totalMonto.toLocaleString(); // Agregar separadores de miles
                document.getElementById('dailyTotalAmount').textContent = 'Valor Total: ' + formattedTotalMonto + ' Gs.'; 
            })
            .catch(error => console.error('Error obteniendo datos diarios:', error));
    }
});
