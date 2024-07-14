;
(function($) {

    const dates = <?php echo json_encode($dates); ?>;
    const durations = <?php echo json_encode($durations); ?>;

    function renderChart(dates, durations) {
        const ctx = document.getElementById('courseDurationChart').getContext('2d');

        const chartData = {
            labels: dates,
            datasets: [{
                label: 'Total Duration (minutes)',
                data: durations,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        if (window.courseDurationChart) {
            window.courseDurationChart.destroy();
        }

        window.courseDurationChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Duration (minutes)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
    }

    renderChart(dates, durations);


})(jQuery);