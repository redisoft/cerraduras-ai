<?php
echo'
<input type="hidden" value="'.$idCuenta.'" name="txtIdCuenta" id="txtIdCuenta"/>
<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Editar cuenta de banco</th>
	</tr>
	<tr>
	<td class="key">Banco:</td>
	<td>
		<input type="text" class="cajas" name="txtBanco" id="txtBanco" style="width:200px" value="'.$cuenta->banco.'"/>
	</td>
	</tr>
	
	<tr>
		<td class="key">Sucursal:</td>
		<td>
			<input type="text" value="'.$cuenta->sucursal.'" class="cajas" name="txtSucursal" id="txtSucursal" style="width:200px"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<input type="text" value="'.$cuenta->cuenta.'" class="cajas" name="txtCuenta" id="txtCuenta" style="width:200px"/>
		</td>
	</tr>
	<tr>
		<td class="key">Clabe:</td>
		<td>
			<input type="text" value="'.$cuenta->clabe.'" class="cajas" name="txtClabe" id="txtClabe" style="width:200px"/>
		</td>
	</tr>
</table>';