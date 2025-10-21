<?php
echo '
<form id="frmCorte" name="frmCorte">
	<table class="admintable" width="100%" >
		<tr>
			<td class="key">Fecha:</td>
			<td>'.obtenerFechaMesCortoHora(date('Y-m-d H:i:s')).'</td>
		</tr>
		
		<tr>
			<td class="key">Tienda:</td>
			<td>'.$tienda->nombre.'</td>
		</tr>
		
		<tr>
			<td class="key">Vendedor:</td>
			<td class="">'.$usuario->nombre.'</td>
		</tr>
		
		<!--<tr>
			<td class="key">Total:</td>
			<td>$'.number_format($total,2).'</td>
		</tr>-->
		
		<tr>
			<td class="key">Efectivo:</td>
			<td>
				<input type="text" class="cajas" id="txtEfectivo" name="txtEfectivo" onkeypress="return soloDecimales(event)" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Comentarios:</td>
			<td>
				<textarea class="TextArea" id="txtComentariosCorte" name="txtComentariosCorte" style="height:50px; width:300px"></textarea>
				<input type="hidden" id="txtTotalCorte" name="txtTotalCorte" value="'.$total.'" />
			</td>
		</tr>
	</table>
</form>';

if($cortes!=null)
{
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="6">Detalles de cortes</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Usuario</th>
			<th>Total</th>
			<th>Efectivo</th>
			<th>Comentarios</th>
		</tr>';
	$i=1;
	foreach($cortes as $row)
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td>'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td>'.$row->usuario.'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
			<td align="right">$'.number_format($row->efectivo,2).'</td>
			<td>'.$row->comentarios.'</td>
		</tr>';
		
		$i++;
	}
	
	echo'
	</table>';
	
}
