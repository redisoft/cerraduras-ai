<?php
echo '
<script>
$(document).ready(function()
{
	$("#txtNombre").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerDepartamentosRepetidos",
		
		select:function( event, ui)
		{
			notify("El departamento ya esta registrado",500,5000,"error",5,5);
			document.getElementById("txtNombre").reset();
		}
	});
});
</script>
		
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input style="width:300px;"  name="txtNombre" id="txtNombre" type="text" class="cajas"  />
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