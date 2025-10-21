<?php
echo '
<table class="admintable" width="100%">
	<tr>
		<td class="key">Cliente:</td>
		<td colspan="3">
		'.$cotizacion->empresa.'
		<input type="hidden" id="txtIdCliente" value="'.$cotizacion->idCliente.'" />
		<input type="hidden" id="txtIdCotizacion" value="'.$cotizacion->idCotizacion.'" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Buscar producto:</td>
		<td colspan="3">
			<input type="text" class="cajas" id="txtBuscarProducto" onkeyup="obtenerProductosVenta()" style="width:600px"  />
		</td>
	</tr>

	<tr>
		<td class="key">Cotización:</td>
		<td>
			<input type="text" class="cajas" id="txtSerie" readonly="readonly" value="'.$cotizacion->serie.'" style="width:130px"  />
		</td>
		<td class="key">Divisa:</td>
		<td>
			<select id="selectDivisas" name="selectDivisas" class="cajas">';
				
				foreach($divisas as $row)
				{
					$seleccionado=$row->idDivisa==$cotizacion->idDivisa?'selected="selected"':'';
					
					echo '<option '.$seleccionado.' value="'.$row->idDivisa.'">'.$row->nombre.' ('.$row->tipoCambio.')</option>';
				}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha de cotización:</td>
		<td>
			<input readonly="readonly" value="'.substr($cotizacion->fecha,0,16).'" type="text" class="cajas" id="txtFechaCotizacion" style="width:120px"  />
		</td>
		<td class="key">Fecha de entrega:</td>
		<td>
			<input readonly="readonly" value="'.substr($cotizacion->fechaEntrega,0,16).'" type="text" class="cajas" id="txtFechaEntrega"  style="width:120px"  />
		</td>
		<script>
			$("#txtFechaCotizacion,#txtFechaEntrega").datetimepicker({changeMonth: true});
		</script>
	</tr>
	
	<tr>
		<td class="key">Comentarios:</td>
		<td colspan="3">
			<textarea class="TextArea" id="txtComentarios" name="txtComentarios" style="height:60px; width:500px">'.$cotizacion->comentarios.'</textarea>
		</td>
	</tr>
	
</table>

<div id="obtenerProductosVenta"></div>
<table class="admintable" width="100%" >
	<tr>
		<th width="75%" style="font-weight:100">
			<table class="admintable" width="100%" id="tablaVentas">
				<tr>
					<th>#</th>
					<th width="15%">Código</th>
					<th>Tipo</th>
					<th>Nombre</th>
					<th>Cantidad</th>
					<th>Precio Unitario</th>
					<th>Total</th>
				</tr>';
				
				$i=1;
				foreach($productos as $row)
				{
					$producto	=strlen($row->nombre)>0?$row->nombre:$row->producto;
					
					echo'
					<tr id="filaProducto'.$i.'">
						<td> <img title="Quitar producto" onclick="quitarProductoKit('.$i.')" src="'.base_url().'img/borrar.png" width="22"  /></td>
						<td>'.$row->codigoInterno.'</td>
						<td>';
							echo $row->servicio==1?'Servicio ('.$row->periodo.')':'Producto';
							$idPeriodo	=$row->servicio==1?$row->idPeriodo:0;
							
							if($row->servicio==1)
							{
								echo '<br /> 
								<input readonly="readonly" value="'.substr($row->fechaInicio,0,16).'" type="text" id="txtFechaInicio'.$i.'" name="txtFechaInicio'.$i.'" class="cajas" style="width:120px; '.($idPeriodo==8?'display:none':'').'" />
								
								
								<script>
									$("#txtFechaInicio'.$i.'").datetimepicker({ changeMonth: true });
								</script>';
							}
							
							echo '<input type="hidden" id="txtIdPeriodo'.$i.'" name="txtIdPeriodo'.$i.'" value="'.$idPeriodo.'"/>';
							
						echo'</td>
						
						<td><textarea style="width:150px" id="txtNombreProducto'.$i.'" class="TextArea">'.$producto.'</textarea></td>
						<td id="filaCantidad'.$i.'">'.number_format($row->cantidad,2).'</td>
						<td align="right">$'.number_format($row->precio,2).'</td>
						<td id="filaTotal'.$i.'" align="right">$'.number_format($row->importe,2).'</td>
						
						<input type="hidden" id="idProducto'.$i.'" value="'.$row->idProduct.'" />
						<input type="hidden" id="cantidadProducto'.$i.'" value="'.$row->cantidad.'" />
						<input type="hidden" id="precioProducto'.$i.'" value="'.$row->precio.'" />
						<input type="hidden" id="totalProducto'.$i.'" value="'.$row->importe.'" />
					</tr>';
					
					$i++;
				}
			echo'
			<input type="hidden" id="txtIndiceCotizacion" value="'.$i.'" />
			</table>
		</th>
		
		<th style="position:absolute; margin-left:2px" width="23%">
			<table class="admintable" width="100%" >
				<tr>
					<td class="key">Subtotal:</td>
					<td><input readonly="readonly" style="width:150px; type="text" class="cajas" id="txtSubTotal" value="'.round($cotizacion->subTotal,2).'" /></td>
				</tr>
				<tr>
					<td class="key">IVA :</td>
					<td align="left">
						<select id="txtIva" class="cajas" style="width:100px" onchange="calcularTotales()">';
							$seleccionado=round($ivas->iva,2)==round($cotizacion->iva*100,2)?'selected="selected"':'';
							echo'<option '.$seleccionado.'>'.$ivas->iva.'</option>';	
							
							$seleccionado=round($ivas->iva2,2)==round($cotizacion->iva/100,2)?'selected="selected"':'';
							echo'<option '.$seleccionado.'>'.$ivas->iva2.'</option>';
							
							$seleccionado=round($ivas->iva3,2)==round($cotizacion->iva*100,2)?'selected="selected"':'';
							echo'<option '.$seleccionado.'>'.$ivas->iva3.'</option>';
						echo'
						</select>
						
					</td>
				</tr>
				<tr>
					<td class="key">Total:</td>
					<td>
						<input readonly="readonly" style="width:150px; type="text" class="cajas" id="txtTotal" value="'.round($cotizacion->total,2).'" />
					</td>
				</tr>
			</table>
		</th>
	</tr>
</table>';