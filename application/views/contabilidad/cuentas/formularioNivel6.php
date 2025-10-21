<?php
$i=1;

echo '
<div id="procesandoNivel6"></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Código agrupador:</td>
		<td>'.$cuenta->nombre.'('.$cuenta->codigo.')
		</td>
	</tr>
	
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<input type="text" class="cajas" id="txtCuentaNivel6" name="txtCuentaNivel6" placeholder="Nivel 6" />
			<input type="hidden" id="txtIdSubCuenta5" name="txtIdSubCuenta5" value="'.$cuenta->idSubCuenta5.'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Código agrupador:</td>
		<td>
			<input type="text" class="cajas" id="txtCodigoAgrupador6" name="txtCodigoAgrupador6" value="'.$cuenta->codigo.'" />
		</td>
	</tr>
</table>';

?>