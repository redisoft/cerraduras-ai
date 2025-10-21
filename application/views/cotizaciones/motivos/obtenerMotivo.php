<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtNombreMotivoEditar" id="txtNombreMotivoEditar" type="text" class="cajas" style="width:300px" value="'.$motivo->nombre.'" />
			<input name="txtIdMotivoEditar" id="txtIdMotivoEditar" type="hidden" value="'.$motivo->idMotivo.'"/>
		</td>
	</tr>	
</table>';