<?php
echo'
<table class="admintable" width="100%">
	<tr>
	<td class="key">Nombre:</td>
	<td>
		<input type="text" class="cajasNormales" value="'.$banco->nombre.'" id="txtNombre" />
		<input type="hidden" value="'.$banco->idBanco.'" id="txtIdBanco" />
	</td>
	</tr>	
</table>';