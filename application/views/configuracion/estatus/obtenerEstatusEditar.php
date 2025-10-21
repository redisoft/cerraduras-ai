<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtNombre" value="'.$estatus->nombre.'" id="txtNombre" type="text" class="cajas" style="width:300px"  />
			<input value="'.$estatus->idEstatus.'" id="txtIdEstatus" type="hidden" />
		</td>
	</tr>	
	<tr>
		<td class="key">Color:</td>
		<td>
			<input style="background-color: '.$estatus->color.'; width:100px" name="txtColor" id="txtColor" value="'.$estatus->color.'" type="text" class="cajas" onclick="startColorPicker(this)" onkeyup="maskedHex(this)" readonly="readonly" placeholder="Seleccione"/>
		</td>
	</tr>
	<tr style="display:none">
		<td class="key">Tipo:</td>
		<td>'.($estatus->cliente=='1'?'Cliente':'Proveedor').'</td>
	</tr>	
</table>';