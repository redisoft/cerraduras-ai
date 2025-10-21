<?php
echo '

	
<div class="row">
	<div class="col-md-12">
		<table class="table table-striped table-responsive">
			<tr>
				<th class="resaltadoIexe">'.obtenerFechaMesLargo($fecha,0).'</th>
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
					<td align="left" width="65%">'.$row->concepto.($row->pagado=='1'?'<br /><i>Pagado</i>':'').'</td>
					<td align="right">$'.number_format($row->importe,decimales).'</td>
				</tr>';
			}
			
			
		echo'
			 <tr>
				<td class="totales">Total</td>
				<td class="totales" align="right">$'.number_format($totalEgresos,decimales).'</td>
			</tr>
		</table>
	</div>';
			
	echo '
	<div class="col-md-6">
		<table class="table table-striped table-responsive" id="tablaDetallesIngresosProyectados">
			<tr>
				<th colspan="2">Créditos</th>
			</tr>';
			
			$totalCreditos	= 0;
			
			foreach($creditos as $row)	
			{
				$totalCreditos	+= $row->pago;
				
				echo '
				<tr>
					<td align="left" width="65%">'.$row->fuente.'</td>
					<td align="right">$'.number_format($row->pago,decimales).'</td>
				</tr>';
			}
			
			
			echo'
			<tr>
				<td class="totales">Total</td>
				<td class="totales" align="right">$'.number_format($totalCreditos,decimales).'</td>
			</tr>
		</table>
	</div>
</div>';




?>



