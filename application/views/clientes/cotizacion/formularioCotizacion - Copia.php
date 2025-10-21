<?php
echo '
<table class="admintable" width="100%">
	<tr>
		<td class="key">Cliente:</td>
		<td colspan="3">
		'.$cliente->empresa.'
		<input type="hidden" id="txtIdCliente" value="'.$idCliente.'" />
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
			<input type="text" class="cajas" id="txtSerie" readonly="readonly" value="'.$serie.'" style="width:130px"  />
			<td class="key">Divisa:</td>
			<td>
				<select id="selectDivisas" name="selectDivisas" class="cajas">';
					
					foreach($divisas as $row)
					{
						echo '<option value="'.$row->idDivisa.'">'.$row->nombre.' ('.$row->tipoCambio.')</option>';
					}
				
				echo'
				</select>
			</td>
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha de cotización:</td>
		<td>
			<input readonly="readonly" value="'.date('Y-m-d H:i').'" type="text" class="cajas" id="txtFechaCotizacion" style="width:120px"  />
		</td>
		<td class="key">Fecha de entrega:</td>
		<td>
			<input readonly="readonly" value="'.date('Y-m-d H:i').'" type="text" class="cajas" id="txtFechaEntrega"  style="width:120px"  />
		</td>
		<script>
			$("#txtFechaCotizacion,#txtFechaEntrega").datetimepicker({changeMonth: true});
		</script>
	</tr>
	
	
	<tr>
		<td class="key">Comentarios:</td>
		<td colspan="3">
			<textarea class="TextArea" id="txtComentarios" name="txtComentarios" style="height:60px; width:500px"></textarea>
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
				</tr>
			</table>
		</th>
		<th style="position:absolute; margin-left:2px" width="23%">
			<table class="admintable" width="100%" >
				<tr>
					<td class="key">Subtotal:</td>
					<td><input readonly="readonly" style="width:150px; type="text" class="cajas" id="txtSubTotal" value="0.00" /></td>
				</tr>
				<tr>
					<td class="key">IVA :</td>
					<td align="left">
						<select id="txtIva" class="cajas" style="width:100px" onchange="calcularTotales()">
							<option>'.$ivas->iva.'</option>	
							<option>'.$ivas->iva2.'</option>
							<option>'.$ivas->iva3.'</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="key">Total:</td>
					<td>
						<input readonly="readonly" style="width:150px; type="text" class="cajas" id="txtTotal" value="0.00" />
					</td>
				</tr>
			</table>
		</th>
	</tr>
</table>';