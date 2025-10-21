<?php
echo '
<script>
$(document).ready(function()
{
	$("#txtNombre").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerLineasRepetidas",
		
		select:function( event, ui)
		{
			notify("La línea ya esta registrada",500,5000,"error",5,5);
			document.getElementById("txtNombre").reset();
		}
	});
});
</script>
<form id="frmLineas" name="frmLineas" action="'.base_url().'configuracion/registrarLinea" method="post" enctype="multipart/form-data">
	<table class="admintable" width="100%;">
		<tr>
			<td class="key">Nombre:</td>
			<td>
				<input name="txtNombre" style="width:300px" id="txtNombre" type="text" class="cajas"  />
			</td>
		</tr>	
		
		<tr>
			<td class="key">Imagen:</td>
			<td>
				<input name="txtImagen" style="height:30px; width:300px" id="txtImagen" type="file" class="cajas"  />
			</td>
		</tr>	
	</table>
</form>';