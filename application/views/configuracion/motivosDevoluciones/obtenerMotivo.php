<?php
echo '
<table class="admintable" width="100%">
	<tr>
		<td class="key">Motivo:</td>
		<td>
			<input type="text" class="cajas" id="txtMotivo" value="'.$motivo->nombre.'" style="width:300px" />
			<input type="hidden" id="txtIdMotivo" value="'.$motivo->idMotivo.'" />
		</td>
	</tr>
</table>';