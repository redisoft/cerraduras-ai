
<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
				Metas y resultados de la campaña
			</div>
			<div class="card-body">
				<div id="graficaProspectos" class="apex-charts"></div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
				Metas y resultados al <?=obtenerFechaMesLargo($fin,0)?>
			</div>
			<div class="card-body">
				<div id="grafiTotales" class="apex-charts"></div>

			</div>
		</div>
	</div>
</div>
<?php
?>

<!-- GRAFICA PROSPECTOS -->
<script>

var options = 
{
    chart: {
        height: 396,
        type: 'bar',
        toolbar: {
            show: false
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            endingShape: 'rounded',
            columnWidth: '55%',
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    colors: ["#537df9", "#8ac542"],
    series: [{
        name: 'Meta',
        data: [<?php foreach($totales as $row){ ?> "<?php echo $row->meta?>"  ,<?php }?>]
    }, {
        name: 'Resultados',
        data: [<?php foreach($totales as $row){ ?> "<?php echo $row->resultado?>"  ,<?php }?>]
    }],
    xaxis: {
        categories: ["Licenciatura","Maestría","Doctorado"],
    },
    legend: {
        offsetY: 10,
    },
    yaxis: {},
    fill: {
        opacity: 1

    },
    // legend: {
    //     floating: true
    // },
    grid: {
        row: {
            colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.2
        },
        borderColor: '#f1f3fa'
    },
    tooltip: {
        y: {
            formatter: function(val) {
                return val + " prospectos" 
            }
        }
    }
}

$(document).ready(function()
{
	var chart = new ApexCharts(
	    document.querySelector("#grafiTotales"),
	    options
	);

	chart.render();
});

//PARA EL PERIODO

var valores = {
    chart: {
        height: 396,
        type: 'bar',
        toolbar: {
            show: false
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            endingShape: 'rounded',
            columnWidth: '55%',
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    colors: ["#537df9", "#8ac542"],
    series: [{
        name: 'Meta',
        data: [<?php foreach($registros as $row){ ?> "<?php echo $row->meta?>"  ,<?php }?>]
    }, {
        name: 'Resultados',
        data: [<?php foreach($registros as $row){ ?> "<?php echo $row->resultado?>"  ,<?php }?>]
    }],
    xaxis: {
        categories: ["Licenciatura","Maestría","Doctorado"],
    },
    legend: {
        offsetY: 10,
    },
    yaxis: {},
    fill: {
        opacity: 1

    },
    // legend: {
    //     floating: true
    // },
    grid: {
        row: {
            colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.2
        },
        borderColor: '#f1f3fa'
    },
    tooltip: {
        y: {
            formatter: function(val) {
                return val + " prospectos" 
            }
        }
    }
}

$(document).ready(function()
{
	var chart = new ApexCharts(
	    document.querySelector("#graficaProspectos"),
	    valores
	);

	chart.render();
});





/*var totales = 
{
	labels: [<?php foreach($totales as $row){ ?> "<?php echo $row->grado?>",<?php }?>],
	datasets: [{
		label: 'Meta',
		backgroundColor: window.chartColors.red,
		data: [
			<?php foreach($totales as $row){ ?> "<?php echo $row->meta?>"  ,<?php }?>
		]
	}, {
		label: 'Resultado',
		backgroundColor: window.chartColors.blue,
		data: [
			
			<?php foreach($totales as $row){ ?> "<?php echo $row->resultado?>"  ,<?php }?>
		]
	}]

};

$(document).ready(function()
{
	var prospectosTotales = document.getElementById("graficaProspectos").getContext("2d");
	
	
	window.barraProspectos = new Chart(prospectosTotales, 
	{
		type: 'bar',
		data: totales,
		showTooltips: false,
		options: 
		{
			title:{
				display:true,
				text:"Metas y resultados acumulados de la campaña"
			},
			tooltips: {
				mode: 'index',
				intersect: false
			},
			responsive: true,
			scales: 
			{
				xAxes: [{
					stacked: false,
				}],
				yAxes: [
				{
					stacked: false,	
					//display: false,
					
				}]
			},
			
			tooltips: 
			{
				enabled: false
			},
			hover: {
				animationDuration: 0
			},
			animation: {
				duration: 1,
				onComplete: function () 
				{
					var chartInstance = this.chart,
						ctx = chartInstance.ctx;
					ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
					ctx.textAlign = 'center';
					ctx.textBaseline = 'bottom';
		
					this.data.datasets.forEach(function (dataset, i) 
					{
						var meta = chartInstance.controller.getDatasetMeta(i);
						meta.data.forEach(function (bar, index) {
							var data = dataset.data[index];                            
							ctx.fillText(data, bar._model.x, bar._model.y -1);
						});
					});
				}
			}
			
			
		}
	});
});*/

</script>