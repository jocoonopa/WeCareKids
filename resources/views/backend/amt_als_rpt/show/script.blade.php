<script>
$(document).ready(function() {
    var Doughnutctx = document.getElementById("myDoughnutChart");
    var tmp = $.parseJSON('{!! \Wck::extractValue2JsonUtf8($levelStats) !!}');
    var provideData = $.map(tmp, function(value, index) {
        return [value];
    });
    
    var myDoughnutChart = new Chart(Doughnutctx, {
        type: 'doughnut',
        data: {
            labels: [
                "视觉", 
                "听觉", 
                "触觉", 
                "前庭觉", 
                "本体觉", 
                "动作计划", 
                "姿势控制", 
                "移位能力", 
                "协调能力"
            ],
            datasets: [{
                label: '等级',
                data: provideData,
                backgroundColor: [
                    'rgba(255, 129, 129, 1)',
                    'rgba(255, 217, 97, 1)',
                    'rgba(255, 255, 105, 1)',
                    'rgba(199, 230, 164, 1)',
                    'rgba(190, 244, 214, 1)',
                    'rgba(151, 228, 255, 1)',
                    'rgba(38, 188, 244, 1)',
                    'rgba(207, 175, 231, 1)',
                    'rgba(255, 183, 255, 1)'
                ],
                hoverBackgroundColor: [
                    'rgba(255, 129, 129, 1)',
                    'rgba(255, 217, 97, 1)',
                    'rgba(255, 255, 105, 1)',
                    'rgba(199, 230, 164, 1)',
                    'rgba(190, 244, 214, 1)',
                    'rgba(151, 228, 255, 1)',
                    'rgba(38, 188, 244, 1)',
                    'rgba(207, 175, 231, 1)',
                    'rgba(255, 183, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            cutoutPercentage: 75,
            legend: {
                position: 'top',
                onClick: {
                    hidden: false
                }
            }
        },
    });
    //######### Insert Text In myDoughnutChart #########//
    Chart.Chart.pluginService.register({
        beforeDraw: function(chart) {
            var width = myDoughnutChart.chart.width;
            var height = myDoughnutChart.chart.height;
            var ctx = myDoughnutChart.chart.ctx;
            ctx.restore();
            var fontSize = (height / 140).toFixed(2);

            ctx.font = fontSize + "em sans-serif";
            ctx.textBaseline = "middle";
            ctx.fillStyle = 'black';
            var text01 = "Level";
            var textX01 = Math.round((width - ctx.measureText(text01).width) / 2);
            var textY01 = height / 2.1;
            ctx.fillText(text01, textX01, textY01);
            
            var text02 = "{{$avgLevel}}", // 總平均
                textX02 = Math.round((width - ctx.measureText(text02).width) / 2),
                textY02 = height / 1.8; // + parseInt(ctx.font)* */
            
            ctx.fillText(text02, textX02, textY02);
            ctx.font = (fontSize / 2) + "em sans-serif";
            
            var text03 = "发展年龄约为{{\App\Model\Child::getMonthFromMap($avgLevel)}}", // 對應到的年齡
                textX03 = Math.round((width - ctx.measureText(text03).width) / 2),
                textY03 = height / 1.4; // + parseInt(ctx.font)* */
            
            ctx.fillText(text03, textX03, textY03);
            ctx.save();
        }
    });
    var ctx2 = document.getElementById("myBarChart");
    var myBarChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: [
                "视觉", 
                "听觉", 
                "触觉", 
                "前庭觉", 
                "本体觉", 
                "动作计划", 
                "姿势控制", 
                "移位能力", 
                "协调能力"
            ],
            datasets: [{
                label: '等级',
                data: provideData,
                backgroundColor: [
                    'rgba(255, 129, 129, 1)',
                    'rgba(255, 217, 97, 1)',
                    'rgba(255, 255, 105, 1)',
                    'rgba(199, 230, 164, 1)',
                    'rgba(190, 244, 214, 1)',
                    'rgba(151, 228, 255, 1)',
                    'rgba(38, 188, 244, 1)',
                    'rgba(207, 175, 231, 1)',
                    'rgba(255, 183, 255, 1)'
                ],
                borderColor: [
                    'rgba(255, 129, 129, 1)',
                    'rgba(255, 217, 97, 1)',
                    'rgba(255, 255, 105, 1)',
                    'rgba(199, 230, 164, 1)',
                    'rgba(190, 244, 214, 1)',
                    'rgba(151, 228, 255, 1)',
                    'rgba(38, 188, 244, 1)',
                    'rgba(207, 175, 231, 1)',
                    'rgba(255, 183, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        max: 20
                    }
                }]
            }
        }
    });
});

</script>