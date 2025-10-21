<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input  style="width:300px;" name="txtNombre" value="'.$producto->nombre.'" id="txtNombre" type="text" class="cajas"  />
			<input value="'.$producto->idProducto.'" id="txtIdProducto" type="hidden" />
		</td>
	</tr>
	<tr>
		<td class="key">Tipo:</td>
		<td>
			<select id="selectTipoRegistro" name="selectTipoRegistro" style="width:150px" class="cajas" >
				<option value="0">Ingresos y egresos</option>
				<option value="1" '.($producto->tipo=='1'?'selected="selected"':'').'>Ingresos</option>
				<option value="2" '.($producto->tipo=='2'?'selected="selected"':'').'>Egresos</option>
			</select>
		</td>
	</tr>		
</table>';