<?php
echo '
<form id="frmCausas">
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtCausa" id="txtCausa" type="text" class="cajas" style="width:300px" value="'.$causa->nombre.'"  />
			<input value="'.$causa->idCausa.'" id="txtIdCausa" name="txtIdCausa" type="hidden" />
		</td>
	</tr>
</table>
</form>';