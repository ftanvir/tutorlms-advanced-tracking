;
(function($) {

    if ($('#video-watch-time-chart').length) {
        alert
        var ctx = $('#video-watch-time-chart')[0].getContext('2d');

        $.ajax({
            url: tlms_at_vars.tlms_at_ajax_url,
            type: 'POST',
            data: {
                action: 'tlms_at_get_video_watch_time_data',
                nonce: tlms_at_vars.tlms_at_nonce
            },
            success: function(response) {
                if (response.success) {
                    var data = response.data;

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Total Watch Time (hours)',
                                data: data.values,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    beginAtZero: true
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value + ' hours';
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    console.error('Failed to fetch data: ', response.data);
                }
            },
            error: function() {
                console.error('AJAX request failed.');
            }
        });
    }


})(jQuery);