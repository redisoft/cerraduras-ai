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
			
			<span '.($programa->editado=='1'?'style="display:none"':'').'>
				&nbsp;&nbsp;&nbsp;
				Día pago
				<input type="text" class="cajas" name="txtDiaPago" id="txtDiaPago" style="width:50px" onkeypress="return soloNumerico(event)" maxlength="2" value="'.$programa->diaPago.'"/>
			</span>';
			
			if($programa->editado=='1')
			{
				echo '
				&nbsp;&nbsp;&nbsp;
				Día pago: '.$programa->diaPago;
			}
			
		echo'
		</td>
	</tr>
	
	<tr>
		<td class="key">Periodicidad reinscripción:</td>
		<td>
			<input type="text" class="cajas" value="'.$programa->cantidadReinscripcion.'" name="txtPeriodicidadReinscripcion" id="txtPeriodicidadReinscripcion" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2"/>
		</td>
	</tr>
	
	<tr '.($programa->editado=='1'?'style="display:none"':'').'>
		<td class="key">Periodo:</td>
		<td>
			<select class="cajas" id="selectPeriodo" name="selectPeriodo" style="width:300px">';
			
			foreach($periodos as $row)
			{
				echo '<option '.($row->idPeriodo==$programa->idPeriodo?'selected="selected"':'').' value="'.$row->idPeriodo.'">'.$row->nombre.'</option>';
			}
				
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Grado:</td>
		<td>
			<select class="cajas" id="selectGrados" name="selectGrados" style="width:300px">';
			
			foreach($grados as $row)
			{
				echo '<option '.($row->idGrado==$programa->idGrado?'selected="selected"':'').' value="'.$row->idGrado.'">'.$row->nombre.'</option>';
			}
				
			echo'
			</select>
		</td>
	</tr>';
	
	
	if($programa->editado=='1')
	{
		echo '
		<tr>
			<td class="key">Periodo:</td>
			<td>'.$programa->periodo.'</td>
		</tr>';
	}
	
	
	echo'
	<tr>
		<td class="key">Actualizar cantidades:</td>
		<td>
			<input type="checkbox" value="1" name="chkProgramaAlumnos" id="chkProgramaAlumnos" />
		</td>
	</tr>
	
</table>';