<div class="row">
	
	<div class="col-md-6">
		<div class="card">
        	<div class="card-header">
				Metas y resultados de la campaña
			</div>
            
			<div id="graficaProspectos" class="apex-charts"></div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card">
        	<div class="card-header">
				Metas y resultados al <?=obtenerFechaMesLargo($fin,0)?>
			</div>

			<div id="totalesDet" class="apex-chart"></div>	
		</div>
	</div>
</div>

<?php
?>

<!-- GRAFICA PROSPECTOS -->
<script>

$(document).ready(function()
{
	var options = {
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
    colors: ["#537df9", "#8ac542", "#ff5722"],
    series: [{
        name: 'Meta',
        data: [<?php foreach($totales as $row){ ?> "<?php echo $row->meta?>"  ,<?php }?>]
    }, {
        name: 'Resultado',
        data: [<?php foreach($totales as $row){ ?> "<?php echo $row->resultado?>"  ,<?php }?>]
    }],
    xaxis: {
        categories: ["Licenciatura", "Maestria", "Doctorado"],
    },
    legend: {
        offsetY: 10,
    },
    yaxis: {
    },
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
                return val +" inscritos"
            }
        }
    }
}

var chart = new ApexCharts(
    document.querySelector("#totalesDet"),
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

</script>