<script>
$(document).ready(function()
{
	$("#txtBuscarProductoAnden").autocomplete(
	{
		source:base_url+"compras/obtenerProductosAndenCompra/<?php echo $compra->idCompras?>",
		
		select:function( event, ui)
		{
			agregarProductoAnden(ui.item);
		}
	});
});
</script>
<form id="frmAnden">
<?php
echo '
<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Detalles de compra</th>
	</tr>
	<tr>
		<td class="key">Orden:</td>
		<td>'.$compra->nombre.'</td>
	</tr>
	
	<tr>
		<td class="key">Proveedor:</td>
		<td>'.$compra->empresa.'</td>
	</tr>
	
	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input value="'.date('Y-m-d H:i').'" readonly="readonly" id="txtFechaRecibido" name="txtFechaRecibido" type="text" class="cajas" style="width:120px" />
			<script>
				$("#txtFechaRecibido").datetimepicker({ changeMonth: true });
			</script>
			
			<input id="txtIdComprita"   name="txtIdComprita"	type="hidden" value="'.$compra->idCompras.'" />
			
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
			<input id="txtRemision"  name="txtRemision"  type="text" class="cajas" style="width:200px" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Importe:</td>
		<td>
			<input id="txtImporteAnden" name="txtImporteAnden" type="text" class="cajas" style="width:120px" onkeypress="return soloDecimales(event)" />
		</td>
	</tr>
	
	<tr>
		<td colspan="2" align="center">
			 <input type="text" class="cajas" style="width:100px" placeholder="Cantidad" id="txtCantidadAnden" value="1" onkeypress="return soloDecimales(event)" />
			 
			 <input type="text" class="cajas" style="width:400px" placeholder="Código" id="txtBuscarProductoAnden" />
		</td>
	</tr>
</table>

<table class="admintable" style="width:100%" id="tablaAnden">
	<tr>
		<th>-</th>
		<th>Código</th>
		<th>Producto</th>
		<th>Cantidad</th>
		<th>Orden compra</th>
		<th>Diferencia</th>
	</tr>
</table>';

?>
</form>