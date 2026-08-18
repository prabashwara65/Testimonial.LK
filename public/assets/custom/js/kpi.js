$(function () {
    $(document).on('change', '#user', function(){
        team = $('#team');

        if (team.val() != 'any') {
            team.val('any');
            $('select').selectpicker('refresh');
        }
    });

    $(document).on('change', '#team', function(){
        user = $('#user');

        if (user.val() != 'any') {
            user.val('any');
            $('select').selectpicker('refresh');
        }
    });

});

function customFilterHandler() {
    var elem = $('.get-data-holder');
    $(".invalid-feedback").text("");
    $('#'+elem.data('holder')).html("<p class=\"text-center\"><br><i class=\"fa fa-spinner text-center fa-3x fa-spin\"></i></p>");
    var url = elem.data('url');
    var holder = 'kpi-holder';
    var data = {
        "user_id": $('#user').val(),
        "team_id": $('#team').val(),
        "brand_id": $('#brand').val(),
        "start_date": $('#start_date').val(),
        "end_date": $('#end_date').val()
    };
    var post = ajax(url, data, 'get');
    post.done(function (response) {
        if (response.status == 'success') {
            notifications('success', response.message, response.data, response.redirect, response.notifyType, holder);
            drawLineChart();
            drawBarChart();
        } else {
            if (response.notifyType != 'view') {
                permissionDeniedMessage();
            }
            notifications('error', response.message, response.data, response.redirect, response.notifyType);
        }
    });
}
const COLORS = {
    'deep-purple-50'        : '#ede7f6',
    'deep-purple-100'       : '#d1c4e9',
    'deep-purple-200'       : '#b39ddb',
    'deep-purple-300'       : '#9575cd',
    'deep-purple-400'       : '#7e57c2',
    'deep-purple-500'       : '#673ab7',
    'deep-purple-600'       : '#5e35b1',
    'deep-purple-700'       : '#512da8',
    'deep-purple-800'       : '#4527a0',
    'deep-purple-900'       : '#311b92',
    'deep-purple-a100'      : '#b388ff',
    'deep-purple-a200'      : '#7c4dff',
    'deep-purple-a400'      : '#651fff',
    'deep-purple-a700'      : '#6200ea',
    'blue-50'               : '#e3f2fd',
    'blue-100'              : '#bbdefb',
    'blue-200'              : '#90caf9',
    'blue-300'              : '#64b5f6',
    'blue-400'              : '#42a5f5',
    'blue-500'              : '#2196f3',
    'blue-600'              : '#1e88e5',
    'blue-700'              : '#1976d2',
    'blue-800'              : '#1565c0',
    'blue-900'              : '#0d47a1',
    'blue-a100'             : '#82b1ff',
    'blue-a200'             : '#448aff',
    'blue-a400'             : '#2979ff',
};
function drawLineChart() {
    $('.chart').each(function () {
        var id = $(this).attr("id"),
            type = $(this).data("type"),
            labels = $(this).data("labels"),
            series1 = $(this).data("series-1"),
            series2 = $(this).data("series-2"),
            series1Label = $(this).data("series-1-label"),
            series2Label = $(this).data("series-2-label"),
            ctx = this.getContext("2d");

        new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label                : series1Label,
                    backgroundColor      : 'rgba(237, 231, 246, 0.5)',
                    borderColor          : '#673ab7',
                    pointBackgroundColor : '#512da8',
                    borderWidth          : 2,
                    data                 : series1,
                }, {
                    label                : series2Label,
                    backgroundColor      : 'rgba(232, 245, 233, 0.5)',
                    borderColor          : '#2196f3',
                    pointBackgroundColor : '#1976d2',
                    borderWidth          : 2,
                    data                 : series2,
                }],
            },

            options: {
                legend: {
                    display: true,
                },
                elements: {
                    line: {
                        tension: 0
                    }
                },
                tooltips: {
                    intersect: false,
                    mode: 'index'
                }
            },

        });
    });
}

function drawBarChart() {
    $('.bar-chart').each(function () {
        var id = $(this).attr("id"),
            type = $(this).data("type"),
            labels = $(this).data("labels"),
            series1 = $(this).data("series-1"),
            series1Label = $(this).data("series-1-label"),
            ctx = this.getContext("2d");

        new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label                : series1Label,
                    backgroundColor      : 'rgba(237, 231, 246, 0.5)',
                    borderColor          : '#673ab7',
                    pointBackgroundColor : '#512da8',
                    borderWidth          : 2,
                    data                 : series1,
                }],
            },

            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        maxBarThickness: 100,
                    }],
                },
                legend: {
                    display: true,
                },
                elements: {
                    line: {
                        tension: 0
                    }
                },
                tooltips: {
                    intersect: false,
                    mode: 'index'
                }
            },

        });
    });
}
