<?php
echo '
<div class="row">
	
<div class="col-md-12">
	<table class="table table-striped table-responsive" width="100%">
		<tr>
			<th class="resaltadoIexe">Proyección financiera al cierre del día '.obtenerFechaMesLargo($fecha,0).'</th>
		</tr>
	</table>
</div>


<div class="col-md-6">
	<table class="table table-striped table-responsive" id="tablaDetallesEgresosProyectados">
		<tr>
			<th colspan="2">Egresos proyectados</th>
		</tr>';
		
		$totalEgresos	= 0;
		
		foreach($egresos as $row)	
		{
			$totalEgresos	+= $row->importe;
			
			echo '
			<tr>
				<td align="left" width="65%">'.$row->concepto.'</td>
				<td align="right">$'.number_format($row->importe,decimales).'</td>
			</tr>';
		}
		
		
	echo'
		 <tr>
            <td class="totales" style="font-size: 16px; text-align: right; vertical-align: middle; font-weight:bold;">Total egresos</td>
            <td class="totales" style="font-size: 16px; text-align: left; vertical-align: middle; font-weight:bold;">$'.number_format($totalEgresos,decimales).'</td>
        </tr>
	</table>
</div>';

echo '
<div class="col-md-6">
    <table class="table table-striped table-responsive" id="tablaDetallesIngresosProyectados">
		<tr>
			<th colspan="2">Ingresos proyectados</th>
		</tr>';
		
		$totalIngresos	= 0;
		
		foreach($ingresos as $row)	
		{
			$totalIngresos	+= $row->importe;
			
			echo '
			<tr>
				<td align="left" width="65%">'.$row->concepto.'</td>
				<td align="right">$'.number_format($row->importe,decimales).'</td>
			</tr>';
		}
		echo '

			<tr>
            <td class="totales" style="font-size: 16px; text-align: right; vertical-align: middle; font-weight:bold;">Total Ingresos</td>
            <td class="totales" style="font-size: 16px; text-align: left; vertical-align: middle; font-weight:bold;">$'.number_format($totalIngresos,decimales).'</td>
        </tr>
		';
		
		echo'
		<tr>
			<th colspan="2">Recursos disponibles</th>
		</tr>
        
        <tr>
            <td align="left">Cuentas</td>
            <td align="right">$'.number_format($cuentas,decimales).'</td>
        </tr>
        
        <tr>
            <td align="left">Efectivo</td>
            <td align="right">$'.number_format($financiera->efectivo,decimales).'</td>
        </tr>
        
        <tr>
            <td class="totales" style="font-size: 16px; text-align: right; vertical-align: middle; font-weight:bold;">Total recursos</td>
            <td class="totales" style="font-size: 16px; text-align: ; vertical-align: middle; font-weight:bold;">$'.number_format($financiera->efectivo+$cuentas+$totalIngresos,decimales).'</td>
        </tr>
    </table>
</div>

<div class="col-md-12">
    <table class="table table-striped dt-responsive nowrap">
		<tr>
			<th style="font-weight: bold; font-size:20px; ">Total: $'.number_format($financiera->efectivo+$cuentas+$totalIngresos-$totalEgresos,decimales).'</th>
		</tr>
	</table>
</div>

</div>';




?>



