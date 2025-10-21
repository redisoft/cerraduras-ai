<?php
echo'
<script>
$(document).ready(function()
{
	$("#txtProducto").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerProductosRepetidos",
		
		select:function( event, ui)
		{
			notify("El producto ya esta registrado",500,5000,"error",5,5);
			document.getElementById("txtProducto").reset();
		}
	});
});
</script>
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Nombre</td>
		<td>
			<input type="text" style="width:280px" class="cajas" id="txtProducto" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Tipo:</td>
		<td>
			<select id="selectTipoRegistro" name="selectTipoRegistro" style="width:150px" class="cajas" >
				<option value="0">Ingresos y egresos</option>
				<option value="1">Ingresos</option>
				<option value="2">Egresos</option>
			</select>
		</td>
	</tr>	
</table>';