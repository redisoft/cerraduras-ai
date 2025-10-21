<?php
$i=1;

echo '
<div id="editandoNivel4"></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<input type="text" class="cajas" id="txtEditarCuentaNivel4" name="txtEditarCuentaNivel4" placeholder="Nivel 4" value="'.$cuenta->nombre.'" />
			<input type="hidden" id="txtIdSubCuenta4" name="txtIdSubCuenta4" value="'.$cuenta->idSubCuenta4.'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Código agrupador:</td>
		<td>
			<input type="text" class="cajas" id="txtEditarCodigoAgrupador4" name="txtEditarCodigoAgrupador4" value="'.$cuenta->codigoAgrupador.'" />
		</td>
	</tr>
	
</table>';

?>