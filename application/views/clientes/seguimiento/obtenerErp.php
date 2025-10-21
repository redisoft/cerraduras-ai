<?php
echo '<input type="hidden" name="txtIdSeguimiento" value="'.$idSeguimiento.'" id="txtIdSeguimiento" /> ';
echo'
<table class="admintable" width="99%;">
	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input type="text" name="txtFechaErp" value="'.$seguimiento->fecha.'" id="txtFechaErp" class="cajas" style="width:160px;" /> 
			<script>
				$("#txtFechaErp").datetimepicker({ changeMonth: true });
			</script>
		</td>
	</tr>
	
	<tr>
		<td class="key">Cliente:</td>
		<td>
			<input type="text" name="txtClienteErp" value="'.$seguimiento->cliente.'" id="txtClienteErp" class="cajas" style="width:200px;"  /> 
		</td>
	</tr>
	
	<tr>
		<td class="key">Status:</td>
		<td>
			<select id="selectStatusErp" name="selectStatusErp" class="cajas">';
			
			foreach($status as $row)
			{
				$seleccionado=$row->idStatus==$seguimiento->idStatus?'selected="selected"':'';
				
				echo '<option '.$seleccionado.' value="'.$row->idStatus.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	
	<tr>
		<td class="key">Responsable:</td>
		<td>
			<select id="selectResponsableErp" name="selectResponsableErp" class="cajas">';
			
			foreach($responsables as $row)
			{
				$seleccionado=$row->idResponsable==$seguimiento->idResponsable?'selected="selected"':'';
				
				echo '<option '.$seleccionado.' value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Comentarios:</td>
		<td>
			<textarea id="txtComentariosErp" name="txtComentariosErp" rows="3" style="width:300px"class="TextArea">'.$seguimiento->comentarios.'</textarea>
		</td>
	</tr>
	
	<tr>
		<td class="key">Observaciones:</td>
		<td>
			<textarea id="txtObservacionesErp" name="txtObservacionesErp" rows="3" style="width:300px"class="TextArea">'.$seguimiento->comentariosExtra.'</textarea>
		</td>
	</tr>
	
</table>';