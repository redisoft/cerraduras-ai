<?php
echo'
<script>
$(document).ready(function()
{
	$("#txtNombre").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerNombresRepetidos",
		
		select:function( event, ui)
		{
			notify("El nombre ya esta registrado",500,5000,"error",5,5);
			document.getElementById("txtNombre").reset();
		}
	});
});
</script>
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Nombre</td>
		<td>
			<input type="text" style="width:280px" class="cajas" id="txtNombre" />
		</td>
	</tr>
</table>';