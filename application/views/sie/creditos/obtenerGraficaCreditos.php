<!--<div style="text-align:center; width:100%" align="center">
	<canvas id="graficaCreditos" style="text-align: center" ></canvas>
</div>-->

<div class="card">
	<div class="card-header">
		<h4>Créditos y préstamos vigentes | Saldo actual con intereses al final del plazo | $<?=number_format($total,decimales)?></h4>
	</div>
	<div class="card-body">
		<div id="crediteishon" class="apex-charts"></div>
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
		      obtenerCreditosDetalles(config.dataPointIndex);
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
    colors: [<?php foreach($creditos as $row){ ?> "<?php echo obtenerColorGraficaRgb()?>"  ,<?php }?>],
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
        data: [<?php foreach($creditos as $row){ ?> <?php echo $row->adeudoActual?>  ,<?php }?>]
    }],
    stroke: {
        width: 1,
      colors: ['#fff']
    },
    xaxis: {
        categories: [<?php foreach($creditos as $row){ ?> "<?php echo $row->fuente?>",<?php }?>],
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
        events: {
		    dataPointSelection: function(event, chartContext, config) {
		      obtenerCreditosDetalles(config.dataPointIndex);
		    }
		}
    },
    series: [<?php foreach($creditos as $row){ ?> <?php echo $row->adeudoActual?>  ,<?php }?>],
    legend: {
        show: true,
        position: 'bottom',
        horizontalAlign: 'center',
        verticalAlign: 'middle',
        floating: false,
        fontSize: '14px',
        offsetX: 0,
        offsetY: -10
    },
    labels: [<?php foreach($creditos as $row){ ?> "<?php echo $row->fuente?>",<?php }?>],
    colors: [<?php foreach($creditos as $row){ ?> "<?php echo obtenerColorGraficaRgb()?>"  ,<?php }?>],
    responsive: [{
        breakpoint: 600,
        options: {
            chart: {
            },
            legend: {
                show: true,
		        position: 'bottom',
		        horizontalAlign: 'left',
		        verticalAlign: 'middle',
		        floating: false,
		        fontSize: '12px',
		        offsetX: 0,
		        offsetY: -5,
		        formatter:function (value) {
			      return value + "\n";
			    }
		        


            },
        }
    }]
}

/* var saldosFecha = 
 {
	type: 'pie',
	data: {
		datasets: [
		{
			data: 
			[
				<?php foreach($creditos as $row){ ?> <?php echo $row->adeudoActual?>  ,<?php }?>
			],
			backgroundColor: 
			[
				<?php foreach($creditos as $row){ ?> "<?php echo obtenerColorGraficaRgb()?>"  ,<?php }?>
				
			],
			label: 'Tipo'
		}],
		labels: 
		[
			<?php foreach($creditos as $row){ ?> "<?php echo $row->fuente?>",<?php }?>
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
			text: "Créditos y préstamos vigentes | Saldo actual con intereses al final del plazo | $<?=number_format($total,decimales)?>"
		}
	},
};*/

$(document).ready(function()
{

	var chart = new ApexCharts(
    document.querySelector("#crediteishon"),
    	opciones
	);

	chart.render();


	/*var saldos 				= document.getElementById("graficaCreditos");
	var saldosDetalle 		= saldos.getContext("2d");
    window.pieEgresos 		= new Chart(saldosDetalle, saldosFecha);
	
	saldos.onclick = function(evt) 
	{
		var activePoints = pieEgresos.getElementsAtEvent(evt);
		if (activePoints[0]) 
		{
			var chartData = activePoints[0]['_chart'].config.data;
			var idx = activePoints[0]['_index'];
			console.log(idx);
			
			var label = chartData.labels[idx];
			var value = chartData.datasets[0].data[idx];
			
			obtenerCreditosDetalles(idx);
		}
    };*/
	
	
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