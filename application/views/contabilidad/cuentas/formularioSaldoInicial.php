<?php
$i=1;

echo '
<div id="procesandoSaldoInicial"></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Código agrupador:</td>
		<td>'.$cuenta->nombre.'('.$cuenta->codigo.')
		</td>
	</tr>
	
	<tr>
		<td class="key">Saldo inicial:</td>
		<td>
			<input type="text" class="cajas" id="txtSaldoInicial" name="txtSaldoInicial" placeholder="$0.00" value="'.round($cuenta->saldoInicial,2).'" />
			<input type="hidden" id="txtIdCuenta" name="txtIdCuenta" value="'.$cuenta->idSubCuenta.'"/>
		</td>
	</tr>
	
</table>';

?>