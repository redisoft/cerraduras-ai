<?php
echo '<div class="ui-state-error" ></div>';
echo'
<input type="hidden" id="txtModuloSie" name="txtModuloSie" value="efectivo" />
<table class="admintable" width="100%">
	<tr>
		<td class="key">Efectivo:</td>
		<td>
			<input type="text" id="txtEfectivo" name="txtEfectivo" value="'.round($financiera->efectivo,decimales).'" style="width:100px" class="cajas" maxlength="10" onkeypress="return soloDecimales(event)" />
		</td>
	</tr>
</table>';