<div align="center">
	<canvas id="graficaSaldosFecha" style="text-align: center" ></canvas>
</div>

<input type="hidden" id="txtFechaSaldos" value="<?=$fecha?>" />

<?php
if($egresos<0)$egresos=$$egresos*-1;
if($saldoDia<0)$saldoDia=$saldoDia*-1;
?>

<!-- GRAFICA PARA EGRESOS POR TIPO -->
<script>
 var saldosFecha = 
 {
	type: 'pie',
	data: {
		datasets: [
		{
			data: 
			[
				<?=$saldoDia?>,<?=$egresos?>
			],
			backgroundColor: 
			[
				window.chartColors.greenHierba,
				window.chartColors.orange,
				
			],
			label: 'Tipo'
		}],
		labels: 
		[
			"Recursos disponibles más ingresos proyectados","Egresos proyectados"
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
			text: 'Saldo al cierre del día <?=obtenerFechaMesLargo($fecha,0)?> | $<?=number_format($importe,decimales)?>'
		},
		
		
		hover: 
		{
			onHover: function(e) 
			{
				$("#graficaSaldosFecha").css("cursor", e[0] ? "pointer" : "pointer");
			}
		}
		
	},
};

$(document).ready(function()
{
	var saldos 				= document.getElementById("graficaSaldosFecha");
	var saldosDetalle 		= saldos.getContext("2d");
    window.pieEgresos 		= new Chart(saldosDetalle, saldosFecha);
	
	saldos.onclick = function(evt) 
	{
		var activePoints = pieEgresos.getElementsAtEvent(evt);
		if (activePoints[0]) 
		{
			var chartData = activePoints[0]['_chart'].config.data;
			var idx = activePoints[0]['_index'];
			
			var label = chartData.labels[idx];
			var value = chartData.datasets[0].data[idx];
			
			obtenerDetalleSaldoFecha(idx)
			
			/*var url = "http://example.com/?label=" + label + "&value=" + value;
			console.log(url);
			alert(idx);*/
		}
    };
	
	
});
</script>