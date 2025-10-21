<?php
$i=1;

echo '
<div id="procesandoNivel4"></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Código agrupador:</td>
		<td>'.$cuenta->nombre.'('.$cuenta->codigo.')
		</td>
	</tr>
	
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<input type="text" class="cajas" id="txtCuentaNivel4" name="txtCuentaNivel4" placeholder="Nivel 4" />
			<input type="hidden" id="txtIdSubCuenta3" name="txtIdSubCuenta3" value="'.$cuenta->idSubCuenta3.'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Código agrupador:</td>
		<td>
			<input type="text" class="cajas" id="txtCodigoAgrupador4" name="txtCodigoAgrupador4" value="'.$cuenta->codigo.'" />
		</td>
	</tr>
	
</table>';

?>