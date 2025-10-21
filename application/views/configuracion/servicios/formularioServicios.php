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
		<td class="key">Tipo:</td>
		<td>
			<select class="cajas" id="selectTipoServicio" name="selectTipoServicio">
				<option value="1">Cliente</option>
				<option value="0">Proveedor</option>
			</select>
		</td>
	</tr>	
</table>';