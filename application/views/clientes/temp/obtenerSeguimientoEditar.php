<script>
	opcionesSeguimiento();
</script>
<?php
echo '
<input type="hidden" name="txtIdSeguimiento" value="'.$idSeguimiento.'" id="txtIdSeguimiento" />
<input type="hidden" name="txtIdCliente" value="'.$seguimiento->idCliente.'" id="txtIdCliente" /> ';
echo'
<table class="admintable" width="99%;">
	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input type="text" name="txtFechaEditar" value="'.substr($seguimiento->fecha,0,10).'" id="txtFechaEditar" class="cajas" style="width:100px;" /> 
			
			Hora
			<input type="text" name="txtHoraSeguimiento" id="txtHoraSeguimiento" class="cajas" style="width:40px;" value="'.substr($seguimiento->fecha,11,5).'" readonly="readonly"  />
			
			<script>
				$("#txtFechaEditar").datepicker({ changeMonth: true });
				$("#txtHoraSeguimiento").timepicker({timeOnly: true});
			</script>
		</td>
	</tr>

	<tr>
		<td class="key">Status:</td>
		<td>
			<select id="selectStatus" name="selectStatus" class="cajas" onchange="opcionesSeguimiento()">';
			
			foreach($status as $row)
			{
				$seleccionado=$row->idStatus==$seguimiento->idStatus?'selected="selected"':'';
				
				echo '<option '.$seleccionado.' value="'.$row->idStatus.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr id="filaServicio">
		<td class="key">Servicio:</td>
		<td>
			<select id="selectServicioEditar" name="selectServicioEditar" class="cajas">';
			
			foreach($servicios as $row)
			{
				$seleccionado=$row->idServicio==$seguimiento->idServicio?'selected="selected"':'';
				
				echo '<option '.$seleccionado.' value="'.$row->idServicio.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Responsable:</td>
		<td>
			<select id="selectResponsableEditar" name="selectResponsableEditar" class="cajas">';
			
			foreach($responsables as $row)
			{
				$seleccionado=$row->idResponsable==$seguimiento->idResponsable?'selected="selected"':'';
				
				echo '<option '.$seleccionado.' value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr id="filaLugar">
		<td class="key">Lugar:</td>
		<td>                       
			<input type="text" name="txtLugarEditar" id="txtLugarEditar" value="'.$seguimiento->lugar.'" 
				class="cajas" style="width:160px;" /> 
		</td>
	</tr>
	
	 <tr id="filaMonto">
		<td class="key">Monto:</td>
		<td>                       
			<input type="text" name="txtMontoEditar" id="txtMontoEditar" value="'.$seguimiento->monto.'" class="cajas" style="width:160px;" /> 
		</td>
	</tr>
	
	<tr id="filaCierre">
		<td class="key">Fecha cierre:</td>
		<td>                           
			<input type="text" name="txtFechaCierreEditar" id="txtFechaCierreEditar" value="'.$seguimiento->fechaCierre.'" class="cajas" style="width:160px;" /> 
			<script>
				$("#txtFechaCierreEditar").datetimepicker({ changeMonth: true });
			</script>
		</td>
	</tr>
	
	<tr>
		<td class="key">Comentarios:</td>
		<td>
			<textarea id="txtComentariosEditar" name="txtComentariosEditar" rows="3" style="width:300px"class="TextArea">'.$seguimiento->comentarios.'</textarea>
		</td>
	</tr>
	
	<tr>
		<td class="key">Observaciones:</td>
		<td>
			<textarea id="txtObservacionesEditar" name="txtObservacionesEditar" rows="3" style="width:300px"class="TextArea">'.$seguimiento->comentariosExtra.'</textarea>
		</td>
	</tr>
	
</table>';