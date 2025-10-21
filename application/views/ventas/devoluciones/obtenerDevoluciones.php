<?php
echo '
<script>
	$("#txtFechaDevolucion").timepicker();
</script>
<form id="frmDevoluciones" name="frmDevoluciones">
	<input type="hidden" id="txtIdCotizacion" name="txtIdCotizacion" value="'.$cotizacion->idCotizacion.'" /> 
	<input type="hidden" id="txtIdCliente" name="txtIdCliente" value="'.$cotizacion->idCliente.'" /> 
	<input type="hidden" id="txtNumeroProductos" name="txtNumeroProductos" value="'.count($productos).'" />
	<input type="hidden" id="txtImporteTotal" name="txtImporteTotal" value="0" />
	
	<input type="hidden" id="txtDisponibleDevolucion" name="txtDisponibleDevolucion" value="'.($cotizacion->pagado-$cotizacion->devoluciones).'" />
	
	<table class="admintable" width="100%" >
		<tr>
			<th colspan="2" class="encabezadoPrincipal">Registrar devolución</th>
		</tr>
		<tr>
			<td class="key">Fecha:</td>
			<td><input type="text" class="cajas" style="width:120px" value="'.date('Y-m-d H:i').'" id="txtFechaDevolucion" name="txtFechaDevolucion" /> </td>
		</tr>
		
		<tr>
			<td class="key">Folio:</td>
			<td>'.$serie[0].'</td>
		</tr>
		
		<tr>
			<td class="key">Devoluciones:</td>
			<td>$'.number_format($cotizacion->devoluciones,decimales).'</td>
		</tr>
		
		<tr>
			<td class="key">Pagado:</td>
			<td>$'.number_format($cotizacion->pagado,decimales).'</td>
		</tr>
		
		<tr>
			<td class="key">Motivo:</td>
			<td>
				<div id="obtenerRegistrosMotivos" style="width:220px; float: left">
					<select class="cajas" style="width:200px" id="selectMotivos" name="selectMotivos">
						<option value="0">Seleccione</option>';
						
						foreach($motivos as $row)
						{
							echo '<option value="'.$row->idMotivo.'">'.$row->nombre.'</option>';
						}
					
					echo'
					</select>
				</div>
				
				<img width="20" height="20" style="float:left" title="Tipo cliente" src="'.base_url().'img/agregar.png" onclick="obtenerCatalogoMotivos()">
			</td>
		</tr>
		
		<tr>
			<td class="key">Producto:</td>
			<td>
				<table class="admintable" width="100%" >
					<tr>
						<th>#</th>
						<th>Producto</th>
						<th>Precio</th>
						<th>Descuento</th>
						<th>Cantidad</th>
						<th>Devueltos</th>
						<th width="15%">Cantidad a devolver</th>
						<th>Importe</th>
					</tr>';
				
				$i=1;
				foreach($productos as $row)
				{
					echo '
					<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
						<td>'.$i.'</td>
						
						<td>'.$row->producto.'</td>
						<td align="right">$'.number_format($row->precio,decimales).'</td>
						<td align="right">$'.number_format($row->descuento/$row->cantidaTotal,decimales).'</td>
						<td align="center">'.number_format($row->cantidad,decimales).'</td>
						<td align="center">'.number_format($row->devueltos,decimales).'</td>
						<td align="center"><input type="text" style="width:70px" id="txtCantidadDevolver'.$i.'" name="txtCantidadDevolver'.$i.'" onchange="calcularImporteDevolucionFila('.$i.')" class="cajas" onkeypress="return soloDecimales(event)" maxlength="15" /></td>
						<td align="right" id="lblImporteProducto'.$i.'">$'.number_format(0,decimales).'</td>
						
						<input type="hidden" id="txtCantidadDisponible'.$i.'" name="txtCantidadDisponible'.$i.'" value="'.($row->cantidad-$row->devueltos).'" />
						<input type="hidden" id="txtIdProducto'.$i.'" name="txtIdProducto'.$i.'" value="'.$row->idProducto.'" />
						<input type="hidden" id="txtIdProductoCatalogo'.$i.'" name="txtIdProductoCatalogo'.$i.'" value="'.$row->idProductoCatalogo.'" />
						<input type="hidden" id="txtPrecioProducto'.$i.'" name="txtPrecioProducto'.$i.'" value="'.$row->precio.'" />
						<input type="hidden" id="txtImporteProducto'.$i.'" name="txtImporteProducto'.$i.'" value="0" />
						<input type="hidden" id="txtDescuentoPorcentaje'.$i.'" name="txtDescuentoPorcentaje'.$i.'" value="'.$row->descuentoPorcentaje.'" />
						<input type="hidden" id="txtDescuentoProducto'.$i.'" name="txtDescuentoProducto'.$i.'" value="0" />
					</tr>';
					
					$i++;
				}
				
			echo'
				</table>
			</td>
		</tr>
		
		<tr>
			<td class="key">Tipo devolución:</td>
			<td>
				<select class="cajas" style="width:150px" id="selectTipoDevolucion" name="selectTipoDevolucion" onchange="configurarTipoNota()">';
				
					foreach($tipos as $row)
					{
						if($row->idTipo!=3)
						{
							echo '<option value="'.$row->idTipo.'">'.$row->nombre.'</option>';	
						}
						
					}	
				
				echo'
				</select>
			</td>
		</tr>
		
		<tr id="filaNotaCredito" style="display:none">
			<td class="key">Nota de crédito:</td>
			<td>
				<img src="'.base_url().'img/notas.png" onclick="obtenerDatosNota()" id="" width="30" height="30" title="Configurar nota de crédito" />
			</td>
		</tr>
		
		<tr id="filaPago" style="display:none">
			<td class="key">Pago:</td>
			<td>
				<img src="'.base_url().'img/pagos.png" onclick="obtenerFormularioDinero()" id="" width="30" height="30" title="Configurar pago" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Importe total:</td>
			<td id="lblImporteTotal">$0.00</td>
		</tr>
		
	</table>
</form>';

if(!empty ($devoluciones))
{
	?>
	<table class="admintable" width="100%" >
    	<?php
        echo '
		<tr>
        	<th class="encabezadoPrincipal" colspan="8">
            	Devoluciones
            </th>
        </tr>';
		?>
    	
	 	<tr>
			<th align="center" valign="middle">#</th>
			<th align="center">Fecha</th>
            <th align="center">Folio</th>
			<th align="center">Motivo</th>
			<th align="center">Producto</th>
            <th align="center">Cantidad</th>
			<th align="center">Importe</th>
            <th align="center">Tipo</th>
		</tr>
	
	<?php
	$i=1;
	foreach ($devoluciones as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td align="center">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center">'.$row->serie.'</td>
			<td align="left">'.$row->motivo.'</td>
			<td align="left">'.$row->producto.'</td>
			<td align="center">'.number_format($row->cantidad,decimales).'</td>
			<td align="right">'."$".number_format($row->importe,decimales).'</td>
			<td align="left">'.$row->tipo.'</td>
		</tr>';
		$i++;
	}
	
	?>
	
	</table>

	<?php
}
else
{
	echo 
	'<div class="Error_validar" style="margin-top:2%; width:95%; margin-left:2px; margin-bottom: 5px;">
		No hay registro de devoluciones
	</div>';
}
?>
