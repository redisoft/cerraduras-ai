<script>
mostrarFechasEstatus()
</script>
<?php
echo'
<div class="ui-state-error" ></div>
<input type="hidden" name="txtIdSeguimiento" value="'.$seguimiento->idSeguimiento.'" id="txtIdSeguimiento" />
<table class="admintable" width="100%">
	<tr>
		<td class="key">Folio:</td>
		<td>'.obtenerFolioSeguimiento($seguimiento->folio).'</td>
	</tr>	

	<tr>
		<td class="key">Estatus:</td>
		<td>
			<select id="selectEstatusEditar" name="selectEstatusEditar" class="cajas" onchange="mostrarFechasEstatus()">';
			
			foreach($estatus as $row)
			{
				echo '<option '.($row->idEstatus==$seguimiento->idEstatus?'selected="selected"':'').' value="'.$row->idEstatus.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr id="filaFechasEstatus" style="display:none">
		<td class="key">Fecha:</td>
		<td>          
			<input type="text" name="txtFechaEstatus" id="txtFechaEstatus" class="cajas" style="width:100px;" value="'.(strlen($seguimiento->fechaResuelta)>2?$seguimiento->fechaResuelta:date('Y-m-d')).'"  /> 
			
			Hora
			<input type="text" name="txtHoraEstatus" id="txtHoraEstatus" class="cajas" style="width:40px;" value="'.(strlen($seguimiento->horaResuelta)>2?substr($seguimiento->horaResuelta,0,5):date('H:i')).'" readonly="readonly"  />
			
			<script>
			$("#txtFechaEstatus").datepicker()
			
			$("#txtHoraEstatus").timepicker({timeOnly: true});
			</script>
			
		</td>
	</tr>
	
</table>';