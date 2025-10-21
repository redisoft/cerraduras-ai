<?php
echo '
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtPrograma" value="'.$programa->nombre.'" id="txtPrograma" type="text" class="cajas" style="width:300px"  />
			<input value="'.$programa->idPrograma.'" id="txtIdPrograma" type="hidden" />
		</td>
	</tr>	
	
	<tr>
		<td class="key">Periodicidad inscripción:</td>
		<td>
			<input type="text" class="cajas" value="'.$programa->cantidadInscripcion.'" name="txtPeriodicidadInscripcion" id="txtPeriodicidadInscripcion" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Periodicidad colegiatura:</td>
		<td>
			<input type="text" class="cajas" value="'.$programa->cantidadColegiatura.'" name="txtPeriodicidadColegiatura" id="txtPeriodicidadColegiatura" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Periodicidad reinscripción:</td>
		<td>
			<input type="text" class="cajas" value="'.$programa->cantidadReinscripcion.'" name="txtPeriodicidadReinscripcion" id="txtPeriodicidadReinscripcion" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2"/>
		</td>
	</tr>
	
	
	<tr>
		<td class="key">Actualizar cantidades:</td>
		<td>
			<input type="checkbox" value="1" name="chkProgramaAlumnos" id="chkProgramaAlumnos" />
		</td>
	</tr>
	
</table>';