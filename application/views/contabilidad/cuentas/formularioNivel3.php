<?php
$i=1;

echo '
<div id="procesandoNivel3"></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Código agrupador:</td>
		<td>'.$cuenta->nombre.'('.$cuenta->codigo.')
		</td>
	</tr>
	
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<input type="text" class="cajas" id="txtCuentaNivel3" name="txtCuentaNivel3" placeholder="Nivel 3" />
			<input type="hidden" id="txtIdSubCuenta" name="txtIdSubCuenta" value="'.$cuenta->idSubCuenta.'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Código agrupador:</td>
		<td>
			<input type="text" class="cajas" id="txtCodigoAgrupador3" name="txtCodigoAgrupador3" value="'.$cuenta->codigo.'" />
		</td>
	</tr>
</table>';

?>