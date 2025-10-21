<?php
echo'
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input type="text" name="txtFechaNota" id="txtFechaNota" class="cajas" style="width:160px;" /> 
			<script>
				$(document).ready(function(){$("#txtFechaNota").datetimepicker()});
			</script>
		</td>
	</tr>
	<tr>
		<td class="key">Responsable:</td>
		<td>
			<select id="selectResponsableNota" name="selectResponsableNota" class="cajas">';
			
			foreach($responsables as $row)
			{
				echo '<option value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	<tr>
		<td class="key">Comentarios:</td>
		<td>
			<textarea id="txtComentariosNotas" name="txtComentariosNotas" rows="3" style="width:300px"class="TextArea"></textarea>
		</td>
	</tr>
</table>';