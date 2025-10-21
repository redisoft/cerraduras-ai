
	<table class="admintable" width="50%" style="float: left">
		<tr>
			<th colspan="2">Fecha actual</th>
		</tr>
		
		<tr>
			<td class="key">Saldo inicial:</td>
			<td>$<?=number_format($inicial,2)?></td>
		</tr>
		
		
		<?php
		foreach($formas as $row)
		{
			echo '
			<tr>
				<td class="key">'.$row->forma.':</td>
				<td>'.number_format($row->total,2).'</td>
			</tr>';
		}
		?>
		
		<tr>
			<td class="key">Tarjetas:</td>
			<td>$<?=number_format($tarjetas,2)?></td>
		</tr>
		
		<tr>
			<td class="key">Vales:</td>
			<td>$<?=number_format($vales,2)?></td>
		</tr>
		<tr>
			<td class="key">Retiros:</td>
			<td>$<?=number_format($retiros,2)?></td>
		</tr>
		<tr>
			<td class="key">Total efectivo:</td>
			<td>$<?=number_format($efectivo-$vales-$retiros+$inicial,2)?></td>
		</tr>
		<tr <?=$pendiente>0?'onclick="obtenerDetallesPendiente()"':''?>>
			<td class="key">Por cobrar:</td>
			<td>$<?=number_format($pendiente,2)?></td>
		</tr>
		
		<tr>
			<td class="key">Envíos cobrados:</td>
			<td>$<?=number_format($enviosCobrados,2)?></td>
		</tr>
		
		<tr>
			<td class="key">Envíos por cobrar:</td>
			<td>$<?=number_format($enviosPendientes,2)?></td>
		</tr>
		
	</table>


	<table class="admintable" width="50%" style="float: left">
		<tr>
			<th colspan="2">Otras fechas</th>
		</tr>
		
		
		<?php
		foreach($formasFecha as $row)
		{
			echo '
			<tr onclick="obtenerDetallesPagos('.$row->idForma.')">
				<td class="key">'.$row->forma.':</td>
				<td>'.number_format($row->total,2).'</td>
			</tr>';
		}
		?>
		
		<tr <?=$tarjetasFecha>0?'onclick="obtenerDetallesPagos(5)"':''?>>
			<td class="key">Tarjetas:</td>
			<td>$<?=number_format($tarjetasFecha,2)?></td>
		</tr>
		
	
		<tr>
			<td class="key">Total efectivo:</td>
			<td>$<?=number_format($efectivoFecha,2)?></td>
		</tr>
		
	</table>

