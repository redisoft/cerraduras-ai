<?php
echo '
<form id="frmProductoRecibido" name="frmProductoRecibido">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Orden:</td>
			<td>'.$compra->nombre.'</td>
		</tr>
		
		<tr>
			<td class="key">Proveedor:</td>
			<td>'.$compra->empresa.'</td>
		</tr>
		
		<tr>
			<td class="key">Producto:</td>
			<td>'.$producto->nombre.'</td>
		</tr>
		<tr>
			<td class="key">Cantidad comprada:</td>
			<td>'.number_format($producto->cantidad,2).'</td>
		</tr>
		
		<tr>
			<td class="key">Fecha entrega:</td>
			<td>'.obtenerFechaMesCorto($producto->fechaEntrega,2).'</td>
		</tr>
		
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input value="'.date('Y-m-d H:i').'" readonly="readonly" id="txtFechaRecibido" name="txtFechaRecibido" type="text" class="cajas" style="width:120px" />
				<script>
					$("#txtFechaRecibido").datetimepicker({ changeMonth: true });
				</script>
			</td>
		</tr>
		<tr>
			<td class="key">Cantidad a recibir:</td>
			<td>
				<input id="txtCantidadRecibir" 	name="txtCantidadRecibir"  	type="text" class="cajas" style="width:100px" />
				<input id="txtIdDetalle"   		name="txtIdDetalle"			type="hidden" value="'.$idDetalle.'" />
				<input id="txtIdComprita"  		name="txtIdComprita"  		type="hidden" value="'.$producto->idCompra.'" />
			</td>
		</tr>
		<tr>
			<td class="key">Factura/Remisión:</td>
			<td>
				<select id="selectFactura" name="selectFactura" class="cajas" style="width:200px">
					<option value="1">Factura</option>
					<option value="0">Remisión</option>
				</select>
				<br />
				<input id="txtRemision" name="txtRemision" type="text" class="cajas" style="width:200px" />
			</td>
		</tr>
		<tr>
			<td class="key">Comprobante:</td>
			<td>
				<input id="txtArchivoComprobante" name="txtArchivoComprobante" type="file" class="cajas" style="width:290px; height:25px;" />
			</td>
		</tr>


		<tr>
			<td class="key">Sucursal:</td>
			<td>
				<select id="selectLicencias" name="selectLicencias" class="cajas" style="width:500px">
					<option value="0">Seleccione</option>';

					foreach($licencias as $row)
					{
						echo '<option value="'.$row->idLicencia.'">'.$row->nombre.'</option>';
					}
					
				echo'
				</select>
			</td>
		</tr>
	</table>
</form>';

if($recibidos!=null)
{
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Cantidad</th>
			<th>Recibio</th>
			<th>Factura</th>
			<th>Remisión</th>
			<th>Sucursal</th>
			<th width="17%">Acciones</th>
		</tr>';
		
	$i=1;
	foreach($recibidos as $row)
	{
		$estilo	= $i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		echo'
		<tr '.$estilo.'>
			<td>'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center">'.$row->cantidad.'</td>
			<td>'.$row->recibio.'</td>
			<td align="center">'.($row->factura==1?$row->remision:'').'</td>
			<td align="center">'.($row->factura==0?$row->remision:'').'</td>
			<td align="left">'.$row->sucursal.'</td>
			<td align="center">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/subir.png" width="22"  onclick="obtenerComprobantesCompras('.$producto->idCompra.','.$row->idRecibido.')"  title="Comprobantes" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnBorrarRecibido'.$i.'" src="'.base_url().'img/borrar.png" width="20" title="Borrar" onclick="accesoBorrarProductoRecibido('.$row->idRecibido.')" /><br />
				<a>Comprobantes</a>
				<a id="a-btnBorrarRecibido'.$i.'">Borrar</a>
			</td>
		</tr>';
		
		if($permiso[3]->activo==0)
		{
			echo '
			<script>
				desactivarBotonSistema(\'btnBorrarRecibido'.$i.'\');
			</script>';
		}
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Aun no se han recibido el producto</div>';
}
