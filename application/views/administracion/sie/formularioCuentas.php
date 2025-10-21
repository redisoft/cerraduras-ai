<?php
echo '<div class="ui-state-error" ></div>
<form id="frmCuentas">
	<input type="hidden" id="txtModuloSie" name="txtModuloSie" value="cuentas" />
	<table class="admintable" width="100%">';
	
	foreach($cuentas as $row)
	{
		echo '
		<tr>
			<td class="key">'.$row->banco.' '.$row->cuenta.':</td>
			<td>
				<input type="text" id="txtCuentas'.$row->idCuenta.'" name="txtCuentas'.$row->idCuenta.'" value="'.round($row->saldoManual,decimales).'" style="width:100px" class="cajas" maxlength="10" onkeypress="return soloDecimales(event)" />
			</td>
		</tr>';
	}
		
	echo'
	</table>
</form>';