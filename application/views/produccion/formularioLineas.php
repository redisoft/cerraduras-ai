<?php
echo'
<script>
$(document).ready(function()
{
	$("#txtLinea").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerLineasRepetidas",
		
		select:function( event, ui)
		{
			notify("La línea ya esta registrada",500,5000,"error",5,5);
			document.getElementById("txtLinea").reset();
		}
	});
});
</script>
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Nombre</td>
		<td>
			<input type="text" style="width:280px" class="cajas" id="txtLinea" />
		</td>
	</tr>
</table>';