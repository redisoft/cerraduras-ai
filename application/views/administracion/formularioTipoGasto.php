<?php
echo'
<script>
$(document).ready(function()
{
	$("#txtTipoGasto").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerGastosRepetidos",
		
		select:function( event, ui)
		{
			notify("El gasto ya esta registrado",500,5000,"error",5,5);
			document.getElementById("txtTipoGasto").reset();
		}
	});
});
</script>
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Nombre</td>
		<td>
			<input type="text" style="width:280px" class="cajas" id="txtTipoGasto" />
		</td>
	</tr>
</table>';