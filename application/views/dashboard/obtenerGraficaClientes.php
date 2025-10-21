<canvas id="graficaClientes"></canvas>

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