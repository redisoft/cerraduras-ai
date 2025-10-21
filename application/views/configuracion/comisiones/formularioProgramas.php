<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtPrograma" id="txtPrograma" type="text" class="cajas" style="width:300px"  />
		</td>
	</tr>
	
	<tr>
		<td class="key">Periodicidad inscripción:</td>
		<td>
			<input type="text" class="cajas" name="txtPeriodicidadInscripcion" id="txtPeriodicidadInscripcion" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Periodicidad colegiatura:</td>
		<td>
			<input type="text" class="cajas" name="txtPeriodicidadColegiatura" id="txtPeriodicidadColegiatura" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Periodicidad reinscripción:</td>
		<td>
			<input type="text" class="cajas" name="txtPeriodicidadReinscripcion" id="txtPeriodicidadReinscripcion" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2"/>
		</td>
	</tr>
	
	
	
</table>';