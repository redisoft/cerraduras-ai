<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtNombre" id="txtNombre" type="text" class="cajas" style="width:300px"  />
		</td>
	</tr>
	
	<tr>
		<td class="key">Color:</td>
		<td>
			<input name="txtColor" id="txtColor" type="text" class="cajas" style="width:100px; " onclick="startColorPicker(this)" onkeyup="maskedHex(this)" readonly="readonly" placeholder="Seleccione"/>
		</td>
	</tr>
	
	<tr style="display:none">
		<td class="key">Tipo:</td>
		<td>
			<select class="cajas" id="selectTipoEstatus" name="selectTipoEstatus">
				<option value="1">Cliente</option>
				<option value="0">Proveedor</option>
			</select>
		</td>
	</tr>	
</table>';