<script>

/* ------------------------------------------------------------------------------
 *
 *  # Feedback count column chart
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var FeedbackCountColumns = function() {


    //
    // Setup module components
    //

    // Basic column chart
    var _columnsBasicLightExample = function() {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        // Define element
        var feedback_count_columns_element = document.getElementById('feedback_count_columns');


        //
        // Charts configuration
        //

        if (feedback_count_columns_element) {

            // Initialize chart
            var feedback_count_columns = echarts.init(feedback_count_columns_element);


            //
            // Chart config
            //

            // Options
            feedback_count_columns.setOption({

                // Define colors
                color: ['#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80'],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Chart animation duration
                animationDuration: 750,

                // Setup grid
                grid: {
                    left: 0,
                    right: 40,
                    top: 35,
                    bottom: 0,
                    containLabel: true
                },

                // Add legend
                legend: {
                    data: ['Video', 'Audio', 'Image', 'Text', 'Questionnaire'],
                    itemHeight: 8,
                    itemGap: 20,
                    textStyle: {
                        padding: [0, 5]
                    }
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    }
                },

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    axisLabel: {
                        color: '#333'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        show: true,
                        lineStyle: {
                            color: '#eee',
                            type: 'dashed'
                        }
                    }
                }],

                // Vertical axis
                yAxis: [{
                    type: 'value',
                    axisLabel: {
                        color: '#333'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                        }
                    }
                }],

                // Add series
                series: [
                    {
                        name: 'Video',
                        type: 'bar',
                        data: [
                            @foreach($feedbackCount['videoCount'] as $count)
                                {{ $count }},
                            @endforeach
                            ],
                        
                    },
                    {
                        name: 'Audio',
                        type: 'bar',
                        data: [
                            @foreach($feedbackCount['audioCount'] as $count)
                                {{ $count }},
                            @endforeach
                            ],
                        
                    },
                    {
                        name: 'Image',
                        type: 'bar',
                        data: [
                            @foreach($feedbackCount['imageCount'] as $count)
                                {{ $count }},
                            @endforeach
                            ],
                        
                    },
                    {
                        name: 'Text',
                        type: 'bar',
                        data: [
                            @foreach($feedbackCount['textCount'] as $count)
                                {{ $count }},
                            @endforeach
                            ],
                        
                    },
                    {
                        name: 'Questionnaire',
                        type: 'bar',
                        data: [
                            @foreach($feedbackCount['questionnaireCount'] as $count)
                                {{ $count }},
                            @endforeach
                        ],
                    }
                ]
            });
        }


        //
        // Resize charts
        //

        // Resize function
        var triggerChartResize = function() {
            feedback_count_columns_element && feedback_count_columns.resize();
        };

        // On window resize
        var resizeCharts;
        window.addEventListener('resize', function() {
            clearTimeout(resizeCharts);
            resizeCharts = setTimeout(function () {
                triggerChartResize();
            }, 200);
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _columnsBasicLightExample();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    FeedbackCountColumns.init();
});

</script>