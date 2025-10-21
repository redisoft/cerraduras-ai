<?php
echo '
<script>
$(document).ready(function()
{
	/*$("#txtNombre").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerLineasRepetidas",
		
		select:function( event, ui)
		{
			notify("La línea ya esta registrada",500,5000,"error",5,5);
			document.getElementById("txtNombre").reset();
		}
	});*/
});
</script>
<form id="frmSubLineas" name="frmSubLineas">
	<table class="admintable" width="100%;">
		<tr>
			<td class="key">Sublinea:</td>
			<td>
				<input name="txtSubLinea" style="width:300px" id="txtSubLinea" type="text" class="cajas"  />
			</td>
		</tr>	
	</table>
</form>';