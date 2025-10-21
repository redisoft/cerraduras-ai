<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input  style="width:300px;" name="txtNombre" value="'.$gasto->nombre.'" id="txtNombre" type="text" class="cajas"  />
			<input value="'.$gasto->idGasto.'" id="txtIdGasto" type="hidden" />
		</td>
	</tr>
	<tr>
		<td class="key">Tipo:</td>
		<td>
			<select id="selectTipoRegistro" name="selectTipoRegistro" style="width:150px" class="cajas" >
				<option value="0">Ingresos y egresos</option>
				<option value="1" '.($gasto->tipo=='1'?'selected="selected"':'').'>Ingresos</option>
				<option value="2" '.($gasto->tipo=='2'?'selected="selected"':'').'>Egresos</option>
			</select>
		</td>
	</tr>		
</table>';