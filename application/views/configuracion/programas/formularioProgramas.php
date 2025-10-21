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
			
			&nbsp;&nbsp;&nbsp;
			Día pago
			<input type="text" class="cajas" name="txtDiaPago" id="txtDiaPago" style="width:50px" onkeypress="return soloNumerico(event)" maxlength="2"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Periodicidad reinscripción:</td>
		<td>
			<input type="text" class="cajas" name="txtPeriodicidadReinscripcion" id="txtPeriodicidadReinscripcion" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Periodo:</td>
		<td>
			<select class="cajas" id="selectPeriodo" name="selectPeriodo" style="width:300px">';
			
			foreach($periodos as $row)
			{
				echo '<option value="'.$row->idPeriodo.'">'.$row->nombre.'</option>';
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
				echo '<option value="'.$row->idGrado.'">'.$row->nombre.'</option>';
			}
				
			echo'
			</select>
		</td>
	</tr>
	
	
</table>';