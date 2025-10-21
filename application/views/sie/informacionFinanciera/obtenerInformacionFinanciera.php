<?php
echo '
<br><br>
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header">
                <h4>Recursos disponibles</h4>
            </div>
            <div class="card-body table-border-style">
            	<div class="table-responsive">
            		<table class="table table-striped" id="tablaRecursos">
						<!--<tr>
							<th colspan="2" class="resaltadoIexe" style="font-size: 16px; text-align: left; vertical-align: middle;">Recurso disponible</th>
							<th style="font-size: 16px; text-align: left; vertical-align: middle;; color: green" rowspan="4" class="">$'.number_format($financiera->efectivo+$cuentas,decimales).'</th>
						</tr>-->
						
						<tr>
							<td style="font-size: 16px; text-align: left; vertical-align: middle;">Cuentas</td>
							<td style="font-size: 16px; text-align: center; vertical-align: middle;" align="right">$'.number_format($cuentas,decimales).'</td>
						</tr>
						
						<tr>
							<td style="font-size: 16px; text-align: left; vertical-align: middle;">Efectivo</td>
							<td style="font-size: 16px; text-align: center; vertical-align: middle;" align="right">$'.number_format($financiera->efectivo,decimales).'</td>
						</tr>
						
						<tr class="bg-success">
							<td style="font-size: 16px; text-align: left; vertical-align: middle;">Total disponible</td>
							<td style="font-size: 16px; text-align: center; vertical-align: middle;" align="right">$'.number_format($financiera->efectivo+$cuentas,decimales).'</td>
						</tr>';
					   
						echo'
						<tr>
							<th style="font-size: 16px; text-align: left; vertical-align: middle;" colspan="2" class="resaltadoIexe"><h4>Recursos no disponibles</h4></th>
							
							<!--<th style="font-size: 16px; text-align: center; vertical-align: middle;; color: red" rowspan="4" class="">$'.number_format($financiera->payu+$financiera->paypal,decimales).'</th>-->
						</tr>
						
						<tr>
							<td style="font-size: 16px; text-align: left; vertical-align: middle;">Payu</td>
							<td style="font-size: 16px; text-align: center; vertical-align: middle;" align="right">$'.number_format($financiera->payu,decimales).'</td>
						</tr>
						
						<tr>
							<td style="font-size: 16px; text-align: left; vertical-align: middle;">Paypal</td>
							<td style="font-size: 16px; text-align: center; vertical-align: middle;" align="right">$'.number_format($financiera->paypal,decimales).'</td>
						</tr>
							
						<tr class="bg-warning">
							<td style="font-size: 16px; text-align: left; vertical-align: middle;">Total no disponible</td>
							<td style="font-size: 16px; text-align: center; vertical-align: middle;" align="right">$'.number_format($financiera->payu+$financiera->paypal,decimales).'</td>
						</tr>
						
						
						<!--<tr>
							<td style="font-size: 16px; text-align: left; vertical-align: middle;" colspan="1" class="totales">Total general</td>
							<td style="font-size: 16px; text-align: center; vertical-align: middle;; color: blue" class="totales" align="right">$'.number_format($financiera->efectivo+$cuentas-$financiera->payu-$financiera->paypal,decimales).'</td>
						</tr>-->
					</table>
            	</div>
        	</div>
		</div>
	</div>
	
	
	<div class="col-md-4">
        <div class="card">
        	<div class="card-header">
                <h4>Gráfica de recursos</h4>
            </div>
            <div class="card-body">
                
                <div class="text-center">
                    <div id="simple-pie" class="apex-charts"></div>
                </div>
            </div>
        </div>
    </div>

	<div class="col-md-4">
		<div class="card">
			<div class="card-header">
                <h4>Proyección</h4>
            </div>
            <div class="card-body table-border-style">
            	<div class="table-responsive">
					<table class="table table-striped dt-responsive nowrap" id="tablaCierres">
						<tr>
							<th style="font-size: 16px; text-align: center; vertical-align: middle;" class="resaltadoIexe" onclick="obtenerGraficaSaldosFecha(\''.$fecha.'\',\''.$saldoDia.'\')">Saldo al cierre del día '.obtenerFechaMesLargo($fecha,0).'</th>
						</tr>
						
						<tr>
							<td style="font-size: 16px; text-align: center; vertical-align: middle;" align="center" onclick="obtenerGraficaSaldosFecha(\''.$fecha.'\',\''.$saldoDia.'\')">$'.number_format($saldoDia,decimales).'</td>
						</tr>
						
						<tr>
							<th style="font-size: 16px; text-align: center; vertical-align: middle;" class="resaltadoIexe" onclick="obtenerGraficaSaldosFecha(\''.$fecha2.'\',\''.$saldoDia2.'\')">Saldo al '.obtenerFechaMesLargo($fecha2,0).'</th>
						</tr>
						
						<tr>
							<td style="font-size: 16px; text-align: center; vertical-align: middle;" align="center" onclick="obtenerGraficaSaldosFecha(\''.$fecha2.'\',\''.$saldoDia2.'\')">$'.number_format($saldoDia2,decimales).'</td>
						</tr>
						
						<tr>
							<th style="font-size: 16px; text-align: center; vertical-align: middle;" class="resaltadoIexe" onclick="obtenerGraficaSaldosFecha(\''.$fecha3.'\',\''.$saldoDia3.'\')">Saldo al '.obtenerFechaMesLargo($fecha3,0).'</th>
						</tr>
						
						<tr>
							<td style="font-size: 16px; text-align: center; vertical-align: middle;" align="center" onclick="obtenerGraficaSaldosFecha(\''.$fecha3.'\',\''.$saldoDia3.'\')">$'.number_format($saldoDia3,decimales).'</td>
						</tr>
						
						<tr>
							<th style="font-size: 16px; text-align: center; vertical-align: middle;" class="resaltadoIexe" onclick="obtenerGraficaSaldosFecha(\''.$fecha4.'\',\''.$saldoDia4.'\')">Saldo al '.obtenerFechaMesLargo($fecha4,0).'</th>
						</tr>
						
						<tr>
							<td style="font-size: 16px; text-align: center; vertical-align: middle;" align="center" onclick="obtenerGraficaSaldosFecha(\''.$fecha4.'\',\''.$saldoDia4.'\')">$'.number_format($saldoDia4,decimales).'</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!--<div class="col-md-4">
		<div>
			<canvas id="graficaInformacionFinanciera" ></canvas>
		</div>
	</div>-->

	


</div>';

?>

 <script>
        /*var barChartData = 
		{
            labels: ["Saldos"],
            datasets: [{
                label: 'Recurso no disponible',
                backgroundColor: window.chartColors.red,
                data: [
                    <?=$financiera->payu+$financiera->paypal?>,
                ]
            }, {
                label: 'Recurso disponible',
                backgroundColor: window.chartColors.blue,
                data: [
                    
					<?=$financiera->efectivo+$cuentas?>,
                ]
            }]

        };
        
		$(document).ready(function()
		{
            var ctx = document.getElementById("graficaInformacionFinanciera").getContext("2d");
			
            
			window.myBar = new Chart(ctx, 
			{
                type: 'bar',
                data: barChartData,
				showTooltips: false,
                options: 
				{
                    title:{
                        display:true,
                        text:"Saldos"
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    responsive: true,
                    scales: 
					{
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [
						{
							stacked: true,	
                            display: false,
							
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
									ctx.fillText('$'+agregarComas(data), bar._model.x, bar._model.y -1);
								});
							});
						}
					}
					
					
                }
            });
        });*/

        var options = {
		    chart: {
		        type: 'pie',
		    },
		    series: [<?=$financiera->payu+$financiera->paypal?>, <?=$financiera->efectivo+$cuentas?>],
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
		            },
		            legend: {
		                show: true,
				        position: 'bottom',
				        horizontalAlign: 'left',
				        verticalAlign: 'middle',
				        floating: false,
				        fontSize: '16px; text-align: center; vertical-align: middle;',
				        offsetX: 0,
				        offsetY: -10,
				        formatter: function (value) {return  value;}
				            },
				        }
		    }]

		}

		var chart = new ApexCharts(
		    document.querySelector("#simple-pie"),
		    options
		);

		chart.render();

       
    </script>



