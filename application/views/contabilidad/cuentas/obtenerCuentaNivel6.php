<?php
$i=1;

echo '
<div id="editandoNivel6"></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<input type="text" class="cajas" id="txtEditarCuentaNivel6" name="txtEditarCuentaNivel6" placeholder="Nivel 6" value="'.$cuenta->nombre.'" />
			<input type="hidden" id="txtIdSubCuenta6" name="txtIdSubCuenta6" value="'.$cuenta->idSubCuenta6.'"/>
		</td>
	</tr>
	<tr>
		<td class="key">Código agrupador:</td>
		<td>
			<input type="text" class="cajas" id="txtEditarCodigoAgrupador6" name="txtEditarCodigoAgrupador6" value="'.$cuenta->codigoAgrupador.'" />
		</td>
	</tr>
</table>';

?>