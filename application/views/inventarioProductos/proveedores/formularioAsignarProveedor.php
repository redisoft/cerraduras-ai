<?php
echo'
<script>
	$("#txtBuscarProveedorAsignar").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProveedores",
		
		select:function( event, ui)
		{
			$("#txtIdProveedorAsignar").val(ui.item.idProveedor);
		}
	});
</script>
<table class="admintable" style="width:100%">
	<tr>
		<td class="key">Seleccione proveedor</td>
		<td>

			<input type="text" style="width:98%" class="cajas" id="txtBuscarProveedorAsignar" name="txtBuscarProveedorAsignar" placeholder="Seleccione">
			<input type="hidden" class="cajas" id="txtIdProveedorAsignar" name="txtIdProveedorAsignar" value="0">
			
		</td>
	</tr>
	
</table>';
