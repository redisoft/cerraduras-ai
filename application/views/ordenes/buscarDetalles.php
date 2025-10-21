<?php
if($totalProducido==null)
{
	$totalProducido=0;
}
		

if ($totalProducido=='0' or $totalProducido==null or $totalProducido<$orden->cantidad )
{
	echo'
	<table class="admintable" width="100%">
		<tr>
		<td class="key">Superviso:</td>
		<td><input type="text" name="txtSuperviso" id="txtSuperviso" class="cajas" style="width:160px;" value="'.$this->session->userdata('nombreUsuarioSesion').'"  /> </td>
		</tr>
		<tr>
		  <td class="key">Fecha:</td>
		  <td>
			<input readonly="readonly" name="txtFechaProducido" id="txtFechaProducido" type="text" class="cajasSelect" value="'.date("Y-m-d").'" />
			<input type="hidden" id="txtIdOrden" value="'.$idOrden.'" />
			<input type="hidden" id="txtIdProducto" value="'.$orden->idProducto.'" />
			<input type="hidden" id="txtTotalOrden" value="'.$orden->cantidad.'" />
			<input type="hidden" id="txtTotalProducido" value="'.$totalProducido.'" />
			<input type="hidden" id="txtIdRelacion" value="'.$idRelacion.'" />
			<input type="hidden" id="txtMateriaPrima" value="'.$producto->materiaPrima.'" />
		  </td>
		</tr>	
		<tr>
		  <td class="key">Cantidad:</td>
		  <td>
			  <input type="text" class="cajasSelect" name="txtCantidadProducido" id="txtCantidadProducido" />
		  </td>
		</tr>	
	</table>
	<script>
		$("#txtFechaProducido").datepicker({ changeMonth: true });
	</script>';
}

echo'
<table class="admintable" width="100%" style="margin-top:3px">
	<tr>
	<th>Fecha</th>
	<th>Cantidad</th>
	<th>Superviso</th>
	</tr>';

$i=1;

if($detalles!=NULL)
{
	foreach ($detalles as $row)
	{
		$estilo=$i%2>0?"class='sinSombra'":'class="sombreado"';
		
		echo'
		<tr '.$estilo.'>
			<td align="center"> '.$row->fechaProduccion.' </td>
			<td align="center"> '.$row->cantidad.' </td>
			<td align="center"> '.$row->superviso.' </td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}