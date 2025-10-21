<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Programa:</td>
		<td>
			'.$programa->nombre.'
			<input value="'.$programa->idPrograma.'" id="txtIdPrograma" type="hidden" />
		</td>
	</tr>	
	
	<tr>
		<td class="key">Importe:</td>
		<td>
			<input type="text" class="cajas" value="'.round($programa->importe,decimales).'" name="txtImporte" id="txtImporte" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="10"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Comision:</td>
		<td>
			<input type="text" class="cajas" value="'.round($programa->comision,decimales).'" name="txtComision" id="txtComision" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="5"/>
		</td>
	</tr>
</table>';