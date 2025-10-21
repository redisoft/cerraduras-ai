

<!--<div style="text-align:center; width:100%" align="center">
	<canvas id="graficaEgresos" style="text-align: center" ></canvas>
</div>-->
<div id="graficaEgresos" class="apex-charts"></div>

<!-- GRAFICA CRÉDITOS -->
<script>
	
 
var options = {
    chart: {
        height: 316,
        type: 'pie',
    },
    series: [651,65],
    labels: ["Recurso No Disponible $" , "Recurso Disponible $"],
    colors: ["#ffc160", "#8ac542"],
    legend: {
        show: true,
        position: 'bottom',
        horizontalAlign: 'center',
        verticalAlign: 'middle',
        floating: false,
        fontSize: '16px; text-align: center; vertical-align: middle;',
        offsetX: 0,
        offsetY: 10,
        formatter: function (value) {return  value;}
    },
    responsive: [{
        breakpoint: 600,
        options: {
            chart: {
                height: 240
            },
            legend: {
                show: false
            },
        }
    }]

}


var chart = new ApexCharts(
    document.querySelector("#graficaEgresos"),
    options
);

chart.render();

</script>