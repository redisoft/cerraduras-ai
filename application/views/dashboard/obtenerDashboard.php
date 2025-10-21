
<div style="width:40%; float:left">
	<canvas id="graficaEgresosMeses" style="text-align:center"></canvas>
</div>

<div style="width:40%; float:left">
	<canvas id="graficaIngresosMeses" style="text-align:center"></canvas>
</div>


<div style="width:50%; float:left">
	<canvas id="graficaVentasMeses"></canvas>
</div>

<div style="width:50%; float:left">
	<canvas id="graficaVentasSemana"></canvas>
</div>

<div style="width:50%; float:left">
	<canvas id="graficaFacturas"></canvas>
</div>

<div style="width:40%; float:left; ">
	<canvas id="graficaVentasProductos" style="text-align:center"></canvas>
</div>

<script>
var color = Chart.helpers.color;
var dataVentas = 
{
	labels: [<?php foreach($ventas as $row){ ?> "<?php echo obtenerMesAnio($row->fechaCompra)?>",<?php }?>],
	datasets: [{
		label: 'Ventas',
		backgroundColor: 
		[
			
			window.chartColors.orange,
			window.chartColors.greenHierba,
			window.chartColors.yellow,
			window.chartColors.green,
			window.chartColors.purple,
			window.chartColors.black,
			window.chartColors.marron,
			window.chartColors.pink,
			window.chartColors.blue,
			window.chartColors.red,
		],
		borderWidth: 1,
		data: 
		[
			<?php foreach($ventas as $row){ ?> <?php echo $row->total?>  ,<?php }?>
		]
	}, 
	]
};

$(document).ready(function()
{
	var ventas = document.getElementById("graficaVentasMeses").getContext("2d");
	
	window.myBar = new Chart(ventas, 
	{
		type: 'bar',
		data: dataVentas,
		options: 
		{
			responsive: true,
			legend: 
			{
				position: 'middle',
			},
			title: 
			{
				display: true,
				text: 'Ventas por mes'
			}
		}
	});

});
</script>

<script>
var dataSemana = 
{
	labels: [<?php foreach($ventasSemana as $row){ ?> "<?php echo obtenerDiaActual($row->fechaCompra)?>",<?php }?>],
	datasets: [{
		label: 'Ventas',
		backgroundColor: 
		[
			
			window.chartColors.orange,
			window.chartColors.greenHierba,
			window.chartColors.yellow,
			window.chartColors.green,
			window.chartColors.purple,
			window.chartColors.black,
			window.chartColors.marron,
			window.chartColors.pink,
			window.chartColors.blue,
			window.chartColors.red,
		],
	
		borderWidth: 1,
		data: 
		[
			<?php foreach($ventasSemana as $row){ ?> <?php echo $row->total?>  ,<?php }?>
		]
	}, 
	]
};

$(document).ready(function()
{
	var semana = document.getElementById("graficaVentasSemana").getContext("2d");
	
	window.myBar = new Chart(semana, 
	{
		type: 'bar',
		data: dataSemana,
		options: 
		{
			responsive: true,
			legend: 
			{
				position: 'middle',
			},
			title: 
			{
				display: true,
				text: 'Ventas semana'
			}
		}
	});

});
</script>


<script>
var dataFacturas = 
{
	labels: [<?php foreach($facturas as $row){ ?> "<?php echo obtenerMesAnio($row->fecha)?>",<?php }?>],
	datasets: [{
		label: 'Facturas',
		//backgroundColor: color(window.chartColors.blue).alpha(1).rgbString(),
		backgroundColor: 
		[
			
			window.chartColors.orange,
			window.chartColors.greenHierba,
			window.chartColors.yellow,
			window.chartColors.green,
			window.chartColors.purple,
			window.chartColors.black,
			window.chartColors.marron,
			window.chartColors.pink,
			window.chartColors.blue,
			window.chartColors.red,
		],
		borderWidth: 1,
		data: 
		[
			<?php foreach($facturas as $row){ ?> <?php echo $row->total?>  ,<?php }?>
		]
		
	}, 
	
	
	]

};

$(document).ready(function()
{
	var facturas = document.getElementById("graficaFacturas").getContext("2d");
	
	window.myBar = new Chart(facturas, 
	{
		type: 'bar',
		data: dataFacturas,
		options: 
		{
			responsive: true,
			legend: 
			{
				position: 'middle',
			},
			title: 
			{
				display: true,
				text: 'Facturas por mes'
			}
		}
	});

});
</script>

<script>
 var productosConfig = 
 {
	type: 'pie',
	data: {
		datasets: [
		{
			data: 
			[
				<?php foreach($ventasProductos as $row){ ?> <?php echo $row->total?>  ,<?php }?>
			],
			backgroundColor: 
			[
				window.chartColors.orange,
				window.chartColors.greenHierba,
				window.chartColors.yellow,
				window.chartColors.green,
				window.chartColors.purple,
				window.chartColors.black,
				window.chartColors.marron,
				window.chartColors.pink,
				window.chartColors.blue,
				window.chartColors.red,
			],
			label: 'Productos'
		}],
		labels: 
		[
			<?php foreach($ventasProductos as $row){ ?> "<?php echo $row->producto?>",<?php }?>
		]
	},
	options: 
	{
		responsive: true,
		legend: 
		{
			position: 'left',
		},
		title: 
		{
			display: true,
			text: 'Productos mas vendidos'
		}
	}
};

$(document).ready(function()
{
	var productos 			= document.getElementById("graficaVentasProductos").getContext("2d");
    window.pieProductos 	= new Chart(productos, productosConfig);
});
</script>

<!-- GRAFICA PARA EGRESOS POR TIPO -->
<script>
 var egresosConfig = 
 {
	type: 'pie',
	data: {
		datasets: [
		{
			data: 
			[
				<?php foreach($egresos as $row){ ?> <?php echo $row->total?>  ,<?php }?>
			],
			backgroundColor: 
			[
				window.chartColors.orange,
				window.chartColors.greenHierba,
				window.chartColors.yellow,
				window.chartColors.green,
				window.chartColors.purple,
				window.chartColors.black,
				window.chartColors.marron,
				window.chartColors.pink,
				window.chartColors.blue,
				window.chartColors.red,
			],
			label: 'Tipo'
		}],
		labels: 
		[
			<?php foreach($egresos as $row){ ?> "<?php echo $row->tipo?>",<?php }?>
		]
	},
	options: 
	{
		responsive: true,
		legend: 
		{
			position: 'left',
		},
		title: 
		{
			display: true,
			text: 'Egresos por departamento'
		}
	}
};

$(document).ready(function()
{
	var egresos 		= document.getElementById("graficaEgresosMeses").getContext("2d");
    window.pieEgresos 	= new Chart(egresos, egresosConfig);
});
</script>


<!-- GRAFICA PARA INGRESOS POR TIPO -->
<script>
 var ingresosConfig = 
 {
	type: 'pie',
	data: {
		datasets: [
		{
			data: 
			[
				<?php foreach($ingresos as $row){ ?> <?php echo $row->total?>  ,<?php }?>
			],
			backgroundColor: 
			[
				window.chartColors.orange,
				window.chartColors.greenHierba,
				window.chartColors.yellow,
				window.chartColors.green,
				window.chartColors.purple,
				window.chartColors.black,
				window.chartColors.marron,
				window.chartColors.pink,
				window.chartColors.blue,
				window.chartColors.red,
			],
			label: 'Tipo'
		}],
		labels: 
		[
			<?php foreach($ingresos as $row){ ?> "<?php echo $row->tipo?>",<?php }?>
		]
	},
	options: 
	{
		responsive: true,
		legend: 
		{
			position: 'left',
		},
		title: 
		{
			display: true,
			text: 'Ingresos por departamento'
		}
	}
};

$(document).ready(function()
{
	var ingresos		= document.getElementById("graficaIngresosMeses").getContext("2d");
    window.pieIngresos 	= new Chart(ingresos, ingresosConfig);
});
</script>
