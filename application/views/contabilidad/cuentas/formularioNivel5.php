<?php
$i=1;

echo '
<div id="procesandoNivel5"></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Código agrupador:</td>
		<td>'.$cuenta->nombre.'('.$cuenta->codigo.')
		</td>
	</tr>
	
	<tr>
		<td class="key">Cuenta:</td>
		<td>
			<input type="text" class="cajas" id="txtCuentaNivel5" name="txtCuentaNivel5" placeholder="Nivel 5" />
			<input type="hidden" id="txtIdSubCuenta4" name="txtIdSubCuenta4" value="'.$cuenta->idSubCuenta4.'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Código agrupador:</td>
		<td>
			<input type="text" class="cajas" id="txtCodigoAgrupador5" name="txtCodigoAgrupador5" value="'.$cuenta->codigo.'" />
		</td>
	</tr>
</table>';

?>