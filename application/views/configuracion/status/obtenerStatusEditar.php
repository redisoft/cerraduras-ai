<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtNombre" value="'.$status->nombre.'" id="txtNombre" type="text" class="cajas" style="width:300px"  />
			<input value="'.$status->idStatus.'" id="txtIdStatus" type="hidden" />
		</td>
	</tr>	
	<tr>
		<td class="key">Color:</td>
		<td>
			<input style="background-color: '.$status->color.'; width:100px" name="txtColor" id="txtColor" value="'.$status->color.'" type="text" class="cajas" onclick="startColorPicker(this)" onkeyup="maskedHex(this)" readonly="readonly" placeholder="Seleccione"/>
		</td>
	</tr>
	<tr>
		<td class="key">Tipo:</td>
		<td>'.($status->cliente=='1'?'Cliente':'Proveedor').'</td>
	</tr>	
</table>';