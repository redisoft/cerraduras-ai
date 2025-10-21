<?php
echo '
<script>
	$("#txtBuscarProveedorInventario").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProveedores/0/0/'.$idInventario.'",
		
		select:function( event, ui)
		{
			$("#selectAsociarProveedor").val(ui.item.idProveedor);
		}
	});
</script>

<table class="admintable" width="100%">
	<tr>
		<td class="key">Proveedor: </td>
		<td>
			<input type="text" style="width:400px" class="cajas" id="txtBuscarProveedorInventario" name="txtBuscarProveedorInventario" placeholder="Seleccione">
			<input type="hidden" id="selectAsociarProveedor" name="selectAsociarProveedor" value="0">
			<input type="hidden" id="txtIdInventarioAsociar" name="txtIdInventarioAsociar" value="'.$idInventario.'">
			
		</td>
	</tr>
	<tr>
		<td class="key">Costo:</td>
		<td>
			<input type="text" class="cajas" id="txtCostoProveedor" onkeypress="return soloDecimales(event)" maxlength="15" />
		</td>
	</tr>
</table>';