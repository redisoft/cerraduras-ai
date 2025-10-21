

<!--<div style="text-align:center; width:100%" align="center">
	<canvas id="graficaEgresos" style="text-align: center" ></canvas>
</div>-->
<br><br>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h4>Gráfica egresos</h4>
			</div>
			<div class="card-body">		

				<div id="grafiEgresos" class="apex-charts"></div>
			</div>
		</div>
	</div>

</div>

<?php
?>

<!-- GRAFICA CRÉDITOS -->
<script>
function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

//
// SIMPLE DONUT CHART
//

var opciones = {
    chart: {
        height: 380,
        type: 'bar',
        toolbar: {show: false},
        events: {
		    dataPointSelection: function(event, chartContext, config) {
		    	detallesEgresos();
		    }
		}
    },
    plotOptions: {
        bar: {
            barHeight: '100%',
            distributed: true,
            horizontal: true,
            dataLabels: {
                position: 'bottom'
            },
        }
    },
    colors: [<?php foreach($egresos as $row){ ?> "<?php echo obtenerColorGraficaRgb()?>"  ,<?php }?>],
    dataLabels: {
        enabled: true,
        textAnchor: 'start',
        style: {
            colors: ['#000000'],
            fontSize: '14px',
            fontFamily: 'Helvetica, Arial, sans-serif'
        },
        formatter: function(val, opt) {
            return opt.w.globals.labels[opt.dataPointIndex] + ":  $" + formatNumber(val)
        },
        offsetX: 0
    },
    series: [{
        data: [<?php foreach($egresos as $row){ ?> <?php echo $row->importe?>  ,<?php }?>]
    }],
    stroke: {
        width: 1,
      colors: ['#fff']
    },
    xaxis: {
        categories: [<?php foreach($egresos as $row){ ?> "<?php echo $row->concepto?>",<?php }?>],
        labels: {
            show: false
        }
    },
    yaxis: {
        labels: {
            show: false
        }
    },
    grid: {
		padding: {
			left: -10,
			right: 0
		}
	}
}


var options = {
    chart: {
        type: 'pie',
        events: 
        {
		    dataPointSelection: function(event, chartContext, config) 
		    {
		    	detallesEgresos();
		    }
		},toolbar: { show: true, tools: { selection: false, reset: true } }
    },
    series: [<?php foreach($egresos as $row){ ?> <?php echo $row->importe?>  ,<?php }?>],
    labels: [<?php foreach($egresos as $row){ ?> "<?php echo $row->concepto?>",<?php }?>],
    colors: [<?php foreach($egresos as $row){ ?> "<?php echo obtenerColorGraficaRgb()?>"  ,<?php }?>],
    legend: {
        show: true,
        position: 'bottom',
        horizontalAlign: 'center',
        verticalAlign: 'middle',
        floating: false,
        fontSize: '12px',
        offsetX: 0,
        offsetY: -1
    },
    responsive: [{
        breakpoint: 600,
        options: 
        {
            chart: {
            },
            legend: {
                show: true,
		        position: 'bottom',
		        horizontalAlign: 'center',
		        verticalAlign: 'middle',
		        floating: false,
		        fontSize: '12px',
		        offsetX: 0,
		        offsetY: -5

            },
        }
    }]

};


/*

 var saldosFecha = 
 {
	type: 'pie',
	data: {
		datasets: [
		{
			data: 
			[
				<?php foreach($egresos as $row){ ?> <?php echo $row->importe?>  ,<?php }?>
			],
			backgroundColor: 
			[
				<?php foreach($egresos as $row){ ?> "<?php echo obtenerColorGraficaRgb()?>"  ,<?php }?>
				
			],
			label: 'Tipo'
		}],
		labels: 
		[
			<?php foreach($egresos as $row){ ?> "<?php echo $row->concepto?>",<?php }?>
		]
	},
	options: 
	{
		responsive: true,
		legend: 
		{
			position: 'bottom',
		},
		title: 
		{
			display: true,
			text: "Egresos"
		},
		hover: 
		{
			onHover: function(e) 
			{
				$("#graficaEgresos").css("cursor", e[0] ? "pointer" : "pointer");
			}
		}
	},
};*/




$(document).ready(function()
{
	/*
	var saldos 				= document.getElementById("graficaEgresos");
	var saldosDetalle 		= saldos.getContext("2d");
    window.pieEgresos 		= new Chart(saldosDetalle, saldosFecha);
	
	saldos.onclick = function(evt) 
	{
		var activePoints = pieEgresos.getElementsAtEvent(evt);
		console.log(activePoints);
		if (activePoints[0]) 
		{
			var chartData = activePoints[0]['_chart'].config.data;
			var idx = activePoints[0]['_index'];
			
			var label = chartData.labels[idx];
			var value = chartData.datasets[0].data[idx];
			
			detallesEgresos()
		}
    };*/
	
	var chart = new ApexCharts(document.querySelector("#grafiEgresos"),opciones);
	chart.render();
});

/*
function drawSegmentValues()
{
    for(var i=0; i<myPieChart.segments.length; i++) 
    {
        ctx.fillStyle="white";
        var textSize = canvas.width/10;
        ctx.font= textSize+"px Verdana";
        // Get needed variables
        var value = myPieChart.segments[i].value;
        var startAngle = myPieChart.segments[i].startAngle;
        var endAngle = myPieChart.segments[i].endAngle;
        var middleAngle = startAngle + ((endAngle - startAngle)/2);

        // Compute text location
        var posX = (radius/2) * Math.cos(middleAngle) + midX;
        var posY = (radius/2) * Math.sin(middleAngle) + midY;

        // Text offside by middle
        var w_offset = ctx.measureText(value).width/2;
        var h_offset = textSize/4;

        ctx.fillText(value, posX - w_offset, posY + h_offset);
    }
}*/
</script>