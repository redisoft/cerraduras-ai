<?php
$saldo		=$monto-$caja;

echo'
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Saldo actual</td>
		<td>
			<strong>$ '.number_format($saldo,2).'</strong>
			<input type="hidden" id="txtSaldoCaja" name="txtSaldoCaja" value="'.$saldo.'" />
		</td>
	</tr>
	<tr>
		<td class="key">Concepto</td>
		<td>
			<input type="text" class="cajas" id="txtConcepto" value="'.$cajaChica->concepto.'" />
			<input type="hidden" id="txtIdEgreso" value="'.$idEgreso.'" />
			<input type="hidden" id="txtIdCaja" value="'.$idCaja.'" />
		</td>
	</tr>
	<tr>
		<td class="key">Importe</td>
		<td>
			<input type="text" class="cajas" id="txtImporte" value="'.$cajaChica->importe.'" />
		</td>
	</tr>
</table>';