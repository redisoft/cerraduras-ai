<?php
echo '
<input type="hidden" id="txtIdOrden" value="'.$idOrden.'" />
<input type="hidden" id="txtIdProducto" value="'.$orden->idProducto.'" />
<input type="hidden" id="txtTotalOrden" value="'.$orden->cantidad.'" />
<input type="hidden" id="txtTotalProducido" value="'.$totalProducido.'" />
<input type="hidden" id="txtIdRelacion" value="'.$idRelacion.'" />
<input type="hidden" id="txtMateriaPrima" value="'.$producto->materiaPrima.'" />';

echo'
<table class="admintable" width="100%">
	<tr>
		<td class="key">Producto:</td>
		<td>'.$orden->producto.'</td>
	</tr>
</table>';

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
			<input readonly="readonly" name="txtFechaProducido" id="txtFechaProducido" type="text" class="cajas" style="width:100px" value="'.date("Y-m-d").'" />
			
			</td>
		</tr>	
		
		<tr>
			<td class="key">Fecha caducidad:</td>
			<td>
			<input readonly="readonly" name="txtFechaCaducidad" id="txtFechaCaducidad" type="text" class="cajas" style="width:100px" value="'.date("Y-m-d").'" />
			
			</td>
		</tr>	
		
		<tr>
		  <td class="key">Cantidad:</td>
		  <td>
			  <input type="text" class="cajas" name="txtCantidadProducido" style="width:100px" id="txtCantidadProducido" onkeypress="return soloDecimales(event)" />
		  </td>
		</tr>	
	</table>
	<script>
		$("#txtFechaProducido,#txtFechaCaducidad").datepicker({ changeMonth: true });
	</script>';
}

echo'
<table class="admintable" width="100%" style="margin-top:3px">
	<tr>
		<th>Fecha</th>
		<th>Cantidad</th>
		<th>Superviso</th>
		<th>Acciones</th>
	</tr>';

$i=1;

if($detalles!=null)
{
	foreach ($detalles as $row)
	{
		$estilo=$i%2>0?"class='sinSombra'":'class="sombreado"';
		
		echo'
		<tr '.$estilo.'>
			<td align="center"> '.obtenerFechaMesCortoHora($row->fechaProduccion).' </td>
			<td align="center"> '.$row->cantidad.' </td>
			<td align="center"> '.$row->superviso.' </td>
			<td align="center">
				<img id="btnEditarProductoTerminado'.$i.'" src="'.base_url().'img/editar.png" onclick="accesoEditarProductoTerminado('.$row->idDetalle.')" width="22" height="22" />
				&nbsp;&nbsp;
				<img id="btnBorrarProductoTerminado'.$i.'" src="'.base_url().'img/borrar.png" onclick="accesoBorrarProductoTerminado('.$row->idDetalle.')" width="22" height="22" />
				<br />
				<a id="a-btnEditarProductoTerminado'.$i.'">Editar</a>
				<a id="a-btnBorrarProductoTerminado'.$i.'">Borrar</a>
			</td>
		</tr>';
		
		if($permiso[2]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnEditarProductoTerminado'.$i.'\');
			</script>';
		}
		
		if($permiso[3]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnBorrarProductoTerminado'.$i.'\');
			</script>';
		}
		
		$i++;
	}
	
	echo '</table>';
}