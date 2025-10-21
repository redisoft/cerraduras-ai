<?php
if($totalProceso==null)
{
	$totalProceso=0;
}
else
{
	
}

if($prioridad==1) // La prioridad es uno, se toma como base la cantidad total a producir
{
	$cantidadTotal=$orden->cantidad;
}
else // La prioridad es mayor uno, se toma como base la cantidad total del proceso pasado
{
	$totalProcesoPasado		=$this->ordenes->obtenerTotalProceso($procesoAnterior);
	
	if($totalProcesoPasado==null)
	{
		$cantidadTotal=0;
	}
}


echo'
<table class="admintable" width="100%">
	<tr>
	<td class="key">Superviso:</td>
	<td>
		<input type="text" name="txtSuperviso" id="txtSuperviso" class="cajas" style="width:250px;" value="'.trim($this->session->userdata('nombreUsuarioSesion')).'"  /> 
	</td>
	</tr>
	<tr>
	  <td class="key">Fecha:</td>
	  <td>
		<input readonly="readonly" name="txtFechaProducido" id="txtFechaProducido" class="cajas" type="text" style="width:100px" value="'.date("Y-m-d").'" />
		<input type="hidden" id="txtIdOrden" value="'.$idOrden.'" />
		<input type="hidden" id="txtIdProducto" value="'.$orden->idProducto.'" />
		<input type="hidden" id="txtIdRelacion" value="'.$idRelacion.'" />
		<input type="hidden" id="txtIdRelacionPasada" value="'.$procesoAnterior.'" />
		<input type="hidden" id="txtPrioridad" value="'.$prioridad.'" />
		<input type="hidden" id="txtTotalOrden" value="" />
		<input type="hidden" id="txtTotalProducido" value="" />
	  </td>
	</tr>	
	<tr>
	  <td class="key">Cantidad:</td>
	  <td>
		  <input type="text" class="cajas" name="txtCantidadProducido" id="txtCantidadProducido" style="width:100px" onkeypress="return soloDecimales(event)" />
	  </td>
	</tr>	
</table>
<script>
	$("#txtFechaProducido").datepicker({ changeMonth: true });
</script>';

echo'
<table class="admintable" width="100%" style="margin-top:3px">
	<tr>
		<th>Fecha</th>
		<th>Cantidad</th>
		<th>Superviso</th>
		<!--<th>Acciones</th>-->
	</tr>';

if($detalles!=null)
{
	$i=1;
	
	foreach ($detalles as $row)
	{
		$estilo	= $i%2>0?"class='sinSombra'":'class="sombreado"';
		
		echo'
		<tr '.$estilo.'>
			<td align="center"> '.$row->fechaProduccion.' </td>
			<td align="center"> '.$row->cantidad.' </td>
			<td align="center"> '.$row->superviso.' </td>
			<!--<td align="center">
				<img src="'.base_url().'img/borrar.png" onclick="borrarProductoProceso('.$row->idDetalle.')" width="22" height="22" />
				<br />
				<a>Borrar</a>
			</td>-->
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}