<?php
$i=1;

echo '
<div id="editandoNivel5"></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<input type="text" class="cajas" id="txtEditarCuentaNivel5" name="txtEditarCuentaNivel5" placeholder="Nivel 5" value="'.$cuenta->nombre.'" />
			<input type="hidden" id="txtIdSubCuenta5" name="txtIdSubCuenta5" value="'.$cuenta->idSubCuenta5.'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Código agrupador:</td>
		<td>
			<input type="text" class="cajas" id="txtEditarCodigoAgrupador5" name="txtEditarCodigoAgrupador5" value="'.$cuenta->codigoAgrupador.'" />
		</td>
	</tr>
	
</table>';

?>