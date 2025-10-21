
<script>
	$("#txtBuscarProveedorInventario").autocomplete(
	{
		source: base_url+"configuracion/obtenerProveedores/0/0/0",
		
		select:function( event, ui)
		{
			$("#selectProveedor").val(ui.item.idProveedor);
		}
	});
</script>

<form id="frmAgregarInventario" name="frmAgregarInventario">
<table class="admintable" width="100%;">
    <tr>
        <td class="key">Nombre:</td>
        <td>
        	<input type="text" name="txtNombre" id="txtNombre" class="cajas" style="width:90%;" /> 
        </td>
    </tr>
    
    <tr>
        <td class="key">Código:</td>
        <td>
        	<input type="text" name="txtCodigo" id="txtCodigo" class="cajas" style="width:200px" /> 
        </td>
    </tr>
    
    <tr>
        <td class="key">Unidad:</td>
        <td>
        	<input type="text" name="txtUnidad" id="txtUnidad" class="cajas" style="width:200px" /> 
        </td>
    </tr>
    
    
    
    <?php
    	echo '
		<tr>
			<td class="key">Proveedor: </td>
			<td>
				<input type="text" style="width:400px" class="cajas" id="txtBuscarProveedorInventario" name="txtBuscarProveedorInventario" placeholder="Seleccione">
				<input type="hidden" id="selectProveedor" name="selectProveedor" value="0">
			</td>
	</tr>';
	?>
    
    
    <tr>
        <td class="key">Costo:</td>
        <td>
        <input type="text" class="cajas" name="txtCosto" id="txtCosto" style="width:200px" onkeypress="return soloDecimales(event)" maxlength="15"/>
        </td>
    </tr>
    
    <tr>
        <td class="key">Cantidad inicial:</td>
        <td>
        <input type="text" class="cajas" name="txtCantidad" id="txtCantidad" style="width:200px" onkeypress="return soloDecimales(event)" maxlength="15"/>
        </td>
    </tr>
</table>
</form>

