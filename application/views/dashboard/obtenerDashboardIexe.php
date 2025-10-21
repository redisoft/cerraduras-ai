<div style="width:75%; float:left">
    <canvas id="graficaIngresosEgresos"></canvas>
</div>


<div style="width:25%; float:left">
    <div>
    <?php
    if($ingresosEgresos!=null)
	{
		echo '
		<table class="admintable" width="100%" style="margin-top:63px">
			<tr>
				<th colspan="2" class="resaltadoIexe">'.obtenerFechaMesCortoHora(date('Y-m-d H:i')).'</th>
			</tr>
			<tr>
				<th class="resaltadoIexe">Concepto</th>
				<th class="resaltadoIexe">Total</th>
			</tr>';
		
		$total	= 0;
		$i		= 0;
		foreach($ingresosEgresos as $row)
		{
			$salidas	= $this->reportes->obtenerEgresosBanco($row->idBanco);
			$totales	= $row->total-$salidas;
			
			if($totales>0)
			{
				#$total+=$row->total;
				$total+=$totales;
				
				echo '
				<tr class="'.($i%2>0?'sombreado':'sinSombra').'">
					<td>'.$row->banco.'</td>
					<td align="right">$'.number_format($totales,decimales).'</td>
				</tr>';
				
				$i++;
			}
		}
		
		echo'
			<tr>
				<th class="resaltadoIexe">Total</th>
				<th class="resaltadoIexe" align="right">$'.number_format($total,decimales).'</th>
			</tr>
		</table>';
	}
	?>
    
    </div>
</div>



<div style="width:40%; float:left">
	<canvas id="graficaEgresosMeses" style="text-align:center"></canvas>
</div>

<div style="width:40%; float:left">
	<canvas id="graficaIngresosMeses" style="text-align:center"></canvas>
</div>



<div style="width:50%; float:left; display:none">
	<canvas id="graficaVentasMeses"></canvas>
</div>

<div style="width:50%; float:left; display:none">
	<canvas id="graficaVentasSemana"></canvas>
</div>

<div style="width:50%; float:left; display:none">
	<canvas id="graficaFacturas"></canvas>
</div>

<div style="width:40%; float:left; display:none ">
	<canvas id="graficaVentasProductos" style="text-align:center"></canvas>
</div>

<?php
$totales	= 0;
?>

<script>
        var ingresosEgresos = {
            type: 'line',
            data: {
                labels: [<?php for($i=0;$i<count($meses);$i++){ ?> "<?php echo obtenerMesAnio($meses[$i])?>",<?php }?>],
                datasets: [{
                    label: "Ingresos",
                    backgroundColor: window.chartColors.greenHierba,
                    borderColor: window.chartColors.greenHierba,
                    data: [
                       <?php for($i=0;$i<count($meses);$i++){ ?> "<?php $ingreso=$this->reportes->obtenerIngresosMes($meses[$i],$idCuenta); 
					   $totales+=$ingreso;
					   echo Sprintf("% 01.2f",$ingreso);?>",<?php }?>
                    ],
                    fill: false,
                }, {
                    label: "Egresos",
                    fill: false,
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [
                         <?php for($i=0;$i<count($meses);$i++){ ?> "<?php $egreso=$this->reportes->obtenerEgresosMes($meses[$i],$idCuenta); 
						 $totales-=$egreso;
						 echo Sprintf("% 01.2f",$egreso)?>",<?php }?>
                    ],
                }]
            },
            options: 
			{
				
				
			
                responsive: true,
                title:
				{
                    display:true,
                    text:'Ingresos y egresos'
                },
				
               tooltips: 
			   {
				   mode: 'label',
				   label: 'mylabel',
				   callbacks: 
				   {
					   label: function(tooltipItem, data) 
					   {
							return '$'+agregarComas(tooltipItem.yLabel);
					   }
				   }
				},

				
                hover: 
				{
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: 
						{
                           // display: true,
                            labelString: 'Meses'
                        },
						
						/*ticks: 
						{
							callback: function (value) 
							{
								  return '$'+agregarComas(value);
							}
						}*/
						
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: 
						{
                            //display: true,
                            labelString: 'Miles de pesos',
                        },
						
						ticks: 
						{
							callback: function (value) 
							{
								  return '$'+agregarComas(value);
							},
							<?php
							if($totales==0)
							{
								?>
								stepSize: 1
								<?php
							}
							?>
						}
						
                    }]
                }
            }
        };
		
		$(document).ready(function()
		{
			var ingreEgre = document.getElementById("graficaIngresosEgresos").getContext("2d");
			window.myLine = new Chart(ingreEgre, ingresosEgresos);
		});
    </script>

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


<?='Totales: '.$totales?>