<?php
echo '
<table class="admintable" width="100%">
	<tr>
		<td class="key">Descripción:</td>
		<td>
			<input type="text" class="cajas" id="txtDescripcion" value="'.$zona->descripcion.'" style="width:300px" />
			<input type="hidden" id="txtIdZona" value="'.$zona->idZona.'" />
		</td>
	</tr>
</table>';