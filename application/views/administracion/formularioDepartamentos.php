<?php
echo'
<script>
$(document).ready(function()
{
	$("#txtNombreDepartamento").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerDepartamentosRepetidos",
		
		select:function( event, ui)
		{
			notify("El departamento ya esta registrado",500,5000,"error",5,5);
			document.getElementById("txtNombreDepartamento").reset();
		}
	});
});
</script>
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Nombre</td>
		<td>
			<input type="text" style="width:280px" class="cajas" id="txtNombreDepartamento" />
		</td>
	</tr>
</table>';
?>