<?php
echo '<div class="ui-state-error" ></div>';
echo'
<input type="hidden" id="txtModuloSie" name="txtModuloSie" value="noDisponible" />
<table class="admintable" width="100%">
	<tr>
		<td class="key">Payu:</td>
		<td>
			<input type="text" id="txtPayu" name="txtPayu" value="'.round($financiera->payu,decimales).'" style="width:100px" class="cajas" maxlength="10" onkeypress="return soloDecimales(event)" />
		</td>
	</tr>
	<tr>
		<td class="key">Paypal:</td>
		<td>
			<input type="text" id="txtPaypal" name="txtPaypal" value="'.round($financiera->paypal,decimales).'" style="width:100px" class="cajas" maxlength="10" onkeypress="return soloDecimales(event)" />
		</td>
	</tr>
</table>';