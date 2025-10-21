<?php
echo'
<script>
	$("#txtBuscarProveedor").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProveedores/0/0/0/'.$idServicio.'",
		
		select:function( event, ui)
		{
			$("#txtIdProveedorServicio").val(ui.item.idProveedor);
		}
	});
</script>
<table class="admintable" style="width:100%">
	<tr>
		<td class="key">Seleccione un proveedor</td>
		<td>
			<input type="text" style="width:98%" class="cajas" id="txtBuscarProveedor" name="txtBuscarProveedor" placeholder="Seleccione">
			<input type="hidden" class="cajas" id="txtIdProveedorServicio" name="txtIdProveedorServicio" value="0">
			
		</td>
	</tr>
	<tr>
		<td class="key">Costo</td>
		<td>
			<input type="text" class="cajas" id="txtCostoServicio" style="width:100px" onkeypress="return soloDecimales(event)" />
			<input type="hidden" id="txtIdServicio" value="'.$idServicio.'" />
		</td>
	</tr>
</table>';

if($opciones==1)
{
	echo '
	<table class="admintable" width="100%">
	<tr>
		<th>#</th>
		<th width="45%">Proveedor</th>
		<th>Costo</th>
		<th>Acciones</th>
	</tr>';
	
	$i=1;
	foreach($servicios as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').' id="filaServicioProveedor'.$row->idProveedor.'">
			<td>'.$i.'</td>
			<td>'.$row->empresa.'</td>
			<td align="center">
				$<input type="text" class="cajas" id="txtCostoServicio'.$i.'" value="'.round($row->costo,decimales).'" onkeypress="return soloDecimales(event)" />
			</tde>
			<td align="center">
				<img id="btnEditarCostoProveedor'.$i.'" onclick="accesoEditarCostoProveedorServicio('.$idServicio.','.$row->idProveedor.','.$i.')" src="'.base_url().'img/editar.png" width="22" title="Editar costo" />
				&nbsp;&nbsp;<img id="btnBorrarCostoProveedor'.$i.'" onclick="accesoBorrarCostoProveedorServicio('.$idServicio.','.$row->idProveedor.','.$i.')" src="'.base_url().'img/borrar.png" width="22" title="Quitar asociación" />
				
				<br />
				
				<a id="a-btnEditarCostoProveedor'.$i.'">Editar</a>
				<a id="a-btnBorrarCostoProveedor'.$i.'">Borrar</a>';

				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarCostoProveedor'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0 or count($servicios)==1)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarCostoProveedor'.$i.'\');
					</script>';
				}
				
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo'</table>';
}