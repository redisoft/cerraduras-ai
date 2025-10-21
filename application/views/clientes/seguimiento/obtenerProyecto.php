<?php
echo'
<table class="admintable" width="99%;">
	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input value="'.substr($seguimiento->fecha,0,10).'" value="'.date('Y-m-d').'" type="text" name="txtFechaProyecto" id="txtFechaProyecto" class="cajas" style="width:160px;" /> 
			<input type="hidden" id="txtIdSeguimiento" value="'.$idSeguimiento.'" />		
			<script>
				$(document).ready(function(){$("#txtFechaProyecto").datepicker()});
			</script>
		</td>
	</tr>
	
	<tr>
		<td class="key">Proyecto:</td>
		<td>
			<textarea id="txtProyecto" name="txtProyecto" rows="3" style="width:300px"class="TextArea">'.$seguimiento->proyecto.'</textarea>
		</td>
	</tr>
	
	<tr>
		<td class="key">Meta:</td>
		<td>
			<textarea id="txtMeta" name="txtMeta" rows="3" style="width:300px"class="TextArea">'.$seguimiento->meta.'</textarea>
		</td>
	</tr>
	
	<tr>
		<td class="key">Status:</td>
		<td>
			<select id="selectStatusProyecto" name="selectStatusProyecto" class="cajas">';
			
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
		<td class="key">Tiempo en horas:</td>
		<td>
			<select id="selectTiempo" name="selectTiempo" class="cajas">';
			
			for($i=1;$i<9;$i++)
			{
				$seleccionado=$i==$seguimiento->tiempo?'selected="selected"':'';
				echo '<option '.$seleccionado.' value="'.$i.'">'.$i.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Avance en %:</td>
		<td>
			<input type="text" class="cajas" id="txtAvance" value="'.$seguimiento->avance.'" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Responsable:</td>
		<td>
			<select id="selectResponsableProyecto" name="selectResponsableProyecto" class="cajas">';
			
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
			<textarea id="txtComentariosProyecto" name="txtComentariosProyecto" 
			rows="3" style="width:300px"class="TextArea">'.$seguimiento->comentarios.'</textarea>
		</td>
	</tr>
</table>';