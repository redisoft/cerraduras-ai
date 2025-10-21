<?php
echo'
<script>
	$("#txtBuscarProveedor").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProveedores/'.$idProducto.'",
		
		select:function( event, ui)
		{
			$("#proveedoresProductos").val(ui.item.idProveedor);
		}
	});
</script>
<table class="admintable" style="width:100%">
	<tr>
		<td class="key">Seleccione un proveedor</td>
		<td>
			<!--<select id="proveedoresProductos" class="cajas" style="width:90%">;
				<option value="0">Seleccione</option>';
				
				foreach($proveedores as $row) 
				{
					if($this->inventario->checarProveedorProducto($row->idProveedor,$idProducto)==0)
					{
						echo '<option value="'.$row->idProveedor.'">'.$row->empresa.'</option>';
					}
				}
			
			echo'
			</select>-->
			
			<input type="text" style="width:98%" class="cajas" id="txtBuscarProveedor" name="txtBuscarProveedor" placeholder="Seleccione">
			<input type="hidden" class="cajas" id="proveedoresProductos" name="proveedoresProductos" value="0">
			
		</td>
	</tr>
	<tr>
		<td class="key">Costo</td>
		<td>
			<input type="text" class="cajas" id="txtCostoProducto" style="width:100px" onkeypress="return soloDecimales(event)" />
			<input type="hidden" id="txtIdProducto" value="'.$idProducto.'" />
		</td>
	</tr>
</table>';

if($editar!=0)
{
	echo '
	<table class="admintable" width="99%">
	<tr>
		<th>#</th>
		<th width="45%">Proveedor</th>
		<th>Costo</th>
		<th>Acciones</th>
	</tr>';
	
	$i=1;
	foreach($proveedoresAsociados as $row)
	{
		echo'
		<tr ';
		echo $i%2>0?'class="sinSombra"':'class="sombreado"';
		echo'>
			<td>'.$i.'</td>
			<td>'.$row->empresa.'</td>
			<td align="center">
				$<input type="text" class="cajas" id="txtCostoProveedor'.$i.'" value="'.$row->precio.'" />
			</td>
			<td align="center">';
				
				echo'
				<img id="btnEditarCostoProveedor'.$i.'" onclick="accesoEditarCostoProveedor('.$idProducto.','.$row->idProveedor.','.$i.')" src="'.base_url().'img/editar.png" width="22" title="Editar costo" />
				&nbsp;&nbsp;<img id="btnBorrarProveedorProducto'.$i.'" onclick="accesoBorrarCostoProveedor('.$idProducto.','.$row->idProveedor.','.$i.')" src="'.base_url().'img/borrar.png" width="22" title="Quitar asociación" />
				<br />
				
				<a id="a-btnEditarCostoProveedor'.$i.'">Editar</a>
				<a id="a-btnBorrarProveedorProducto'.$i.'">Borrar</a>';
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarCostoProveedor'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0 or count($proveedoresAsociados)==1)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarProveedorProducto'.$i.'\');
					</script>';
				}
				
				
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo'</table>';
}