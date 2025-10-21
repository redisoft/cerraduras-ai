<?php
$i=1;

echo '
<div id="editandoNivel3"></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<input type="text" class="cajas" id="txtEditarCuentaNivel3" name="txtEditarCuentaNivel3" placeholder="Nivel 3" value="'.$cuenta->nombre.'" />
			<input type="hidden" id="txtIdSubCuenta3" name="txtIdSubCuenta3" value="'.$cuenta->idSubCuenta3.'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Código agrupador:</td>
		<td>
			<input type="text" class="cajas" id="txtEditarCodigoAgrupador3" name="txtEditarCodigoAgrupador3" value="'.$cuenta->codigoAgrupador.'" />
		</td>
	</tr>
	
</table>';

?>