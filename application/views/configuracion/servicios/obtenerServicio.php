<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtNombre" value="'.$servicio->nombre.'" id="txtNombre" type="text" class="cajas" style="width:300px"  />
			<input value="'.$servicio->idServicio.'" id="txtIdServicio" type="hidden" />
		</td>
	</tr>	
	<tr>
		<td class="key">Tipo:</td>
		<td>'.($servicio->cliente=='1'?'Cliente':'Proveedor').'</td>
	</tr>	
</table>';