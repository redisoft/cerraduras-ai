<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>&nbsp;</title>
	<head>
		<style>
			html
			{
				font-family: Sans-Serif;
			}
			body
			{
				margin: 0;
				padding:0;

			}
		</style>
		<script>
			window.print();
		</script>
	</head>
	<body>
<div class="letraGeneral" style="width:316px">
	<table class="admintable" width="100%">
		<tr>
			<td width="30%" class="key">Fecha:</td>
			<td width="30%"><?=obtenerFechaMesLargo($fecha,0)?></td>
			<td width="10%" class="key">Hora:</td>
			<td width="30%"><?=date('H:i:s')?></td>
		</tr>
		<?php
		if($estacion!=null)
		{
			echo '
			<tr>
				<td class="key">Estación:</td>
				<td>'.$estacion->nombre.'</td>
				<td></td>
				<td></td>
			</tr>';
		}
		?>
		
		<tr>
			<td class="key">Saldo inicial:</td>
			<td>$<?=number_format($inicial,2)?></td>
			<td></td>
			<td></td>
		</tr>
		
		<!--<tr>
			<td class="key">Efectivo:</td>
			<td>$<?=number_format($efectivo,2)?></td>
			<td></td>
			<td></td>
		</tr>-->
		
		
		<?php
		foreach($formas as $row)
		{
			echo '
			<tr>
				<td class="key">'.$row->forma.':</td>
				<td>'.number_format($row->total,2).'</td>
				<td></td>
				<td></td>
			</tr>';
		}
		?>
		
		<tr>
			<td class="key">Tarjetas:</td>
			<td>$<?=number_format($tarjetas,2)?></td>
			<td></td>
			<td></td>
		</tr>
		
		<tr>
			<td class="key">Vales:</td>
			<td></td>
			<td></td>
			<td>$<?=number_format($vales,2)?></td>
		</tr>
		<tr>
			
			<td class="key">Retiros:</td>
			<td></td>
			<td></td>
			<td>$<?=number_format($retiros,2)?></td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		
		<tr>
			<td class="key">Total efectivo:</td>
			<td>$<?=number_format($efectivo-$vales-$retiros+$inicial,2)?></td>
			<td></td>
			<td></td>
		</tr>
		
		<tr>
			<td class="key">Por cobrar:</td>
			<td>$<?=number_format($pendiente,2)?></td>
			<td></td>
			<td></td>
		</tr>
		
		<tr>
			<td class="key">Total caja:</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		
		
		<tr>
			<td class="key">Diferencia:</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
	
	<br><br>

	<div align="center">
	________________________________<br>
		Firma
	</div>
</div>
		
</body>
</html>
