document.addEventListener('DOMContentLoaded', function () {
    fetchProgressData();
});

function fetchProgressData() {
    fetch('../../backend/public/get_progress_data.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                drawDueChart(data.future_due_data);
                drawStatusChart(data.card_status_data);
            } else {
                console.error('Failed to load progress data');
            }
        })
        .catch(error => console.error('Error fetching progress data:', error));
}

function drawDueChart(data) {
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function() {
        var dataTable = new google.visualization.DataTable();
        dataTable.addColumn('string', 'Due Date');
        dataTable.addColumn('number', 'Cards Due');
        
        data.forEach(item => {
            dataTable.addRow([item.due_date, parseInt(item.due_count)]);
        });

        var options = {
            title: 'Future Due Cards',
            curveType: 'function',
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('due-chart'));
        chart.draw(dataTable, options);
    });
}

function drawStatusChart(data) {
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function() {
        var dataTable = new google.visualization.DataTable();
        dataTable.addColumn('string', 'Card Status');
        dataTable.addColumn('number', 'Count');
        
        data.forEach(item => {
            dataTable.addRow([item.card_status, parseInt(item.status_count)]);
        });

        var options = {
            title: 'Card Status Distribution',
            pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('status-chart'));
        chart.draw(dataTable, options);
    });
}