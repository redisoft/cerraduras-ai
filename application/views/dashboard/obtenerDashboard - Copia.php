
<div style="width:50%; float:left">
	<canvas id="graficaVentasMeses"></canvas>
</div>

<div style="width:50%; float:left">
	<canvas id="graficaGastosMeses"></canvas>
</div>

<div style="width:50%; float:left">
	<canvas id="graficaGastosVentasMeses"></canvas>
</div>

<div style="width:25%; float:right; ">
	<canvas id="graficaClientes" style="text-align:center"></canvas>
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
var dataGastos = 
{
	labels: [<?php foreach($gastos as $row){ ?> "<?php echo obtenerMesAnio($row->fecha)?>",<?php }?>],
	datasets: [{
		label: 'Gastos',
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
			<?php foreach($gastos as $row){ ?> <?php echo $row->total?>  ,<?php }?>
		]
		
	}, 
	]

};

$(document).ready(function()
{
	var gastos = document.getElementById("graficaGastosMeses").getContext("2d");
	
	window.myBar = new Chart(gastos, 
	{
		type: 'bar',
		data: dataGastos,
		options: 
		{
			responsive: true,
			legend: 
			{
				position: 'top',
			},
			title: 
			{
				display: true,
				text: 'Gastos por mes'
			}
		}
	});

});
</script>


<script>
var dataGastosMeses = 
{
	labels: [<?php foreach($gastosVentas as $row){ ?> "<?php echo obtenerMesAnio($row->fecha)?>",<?php }?>],
	datasets: [{
		label: 'Ventas',
		backgroundColor: color(window.chartColors.blue).alpha(1).rgbString(),
		borderWidth: 1,
		data: 
		[
			<?php foreach($gastosVentas as $row){ ?> <?php echo $row->total?>  ,<?php }?>
		]
		
	}, 
	{
		label: 'Gastos',
		backgroundColor: color(window.chartColors.red).alpha(1).rgbString(),
		borderColor: window.chartColors.blue,
		borderWidth: 1,
		data: [
			<?php foreach($gastosVentas as $row){ ?> <?php echo $row->totalGastos?>  ,<?php }?>
		]
	}
	
	]

};

$(document).ready(function()
{
	var gastosMeses = document.getElementById("graficaGastosVentasMeses").getContext("2d");
	
	window.myBar = new Chart(gastosMeses, 
	{
		type: 'bar',
		data: dataGastosMeses,
		options: 
		{
			responsive: true,
			legend: 
			{
				position: 'top',
			},
			title: 
			{
				display: true,
				text: 'Ventas vs gastos'
			}
		}
	});

});
</script>

<script>

 var config = 
 {
	type: 'pie',
	data: {
		datasets: [
		{
			data: 
			[
				<?php foreach($clientes as $row){ ?> <?php echo $row->numeroClientes?>  ,<?php }?>
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
			label: 'Clientes'
		}],
		labels: 
		[
			<?php foreach($clientes as $row){ ?> "<?php echo $row->descripcion?>",<?php }?>
		]
	},
	options: 
	{
		responsive: true,
		title: 
		{
			display: true,
			text: 'Tipos de cliente'
		}
	}
};

$(document).ready(function()
{
	var ctx = document.getElementById("graficaClientes").getContext("2d");
    window.myPie = new Chart(ctx, config);
});


</script>
