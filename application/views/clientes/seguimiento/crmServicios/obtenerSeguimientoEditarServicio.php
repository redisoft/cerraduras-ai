<script>
	opcionesSeguimiento();
	//opcionesServicios();
</script>
<?php
echo '
<input type="hidden" name="txtIdSeguimiento" value="'.$idSeguimiento.'" id="txtIdSeguimiento" />
<div id="enviandoBitacora"></div>
<table class="admintable" width="100%;">

	<tr>
		<td class="key">Cliente:</td>
		<td>'.$cliente->empresa.'</td>
	</tr>
	
	<tr>
		<td class="key">Folio:</td>
		<td>
			'.obtenerFolioSeguimiento($seguimiento->folio).'
		</td>
	</tr>
	
	<tr>
		<td class="key">CRM:</td>
		<td>
			<select id="selectStatus" name="selectStatus" class="cajas" onchange="opcionesSeguimiento()">';
			
			foreach($status as $row)
			{
				if($row->idStatusIgual!=4 and $row->idStatusIgual!=3)
				{
					echo '<option '.($row->idStatus==$seguimiento->idStatus?'selected="selected"':'').' value="'.$row->idStatus.'|'.$row->idStatusIgual.'">'.$row->nombre.'</option>';
				}
			}
			
			echo'
			</select>
		</td>
	</tr>
	
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

	
	
	<tr id="filaServicio">
		<td class="key">Servicio:</td>
		<td>'.$seguimiento->servicio.'
		<input type="hidden" id="selectServicio" name="selectServicio" value="'.$seguimiento->idServicio.'"></td>
	</tr>
	
	<tr id="filaContacto">
		<td class="key">Contacto:</td>
		<td>
			<select id="selectContactos" name="selectContactos" class="cajas">
				<option value="0">Seleccione</option>';
			
			foreach($contactos as $row)
			{
				echo '<option '.($row->idContacto==$seguimiento->idContacto?'selected="selected"':'').' value="'.$row->idContacto.'">Nombre: '.$row->nombre.', Teléfono: '.$row->telefono.', Email: '.$row->email.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Responsable:</td>
		<td>
			<select id="selectResponsable" name="selectResponsable" class="cajas" onchange="sugerirCorreo()">';
			
			foreach($responsables as $row)
			{
				$seleccionado=$row->idResponsable==$seguimiento->idResponsable?'selected="selected"':'';
				
				echo '<option '.$seleccionado.' value="'.$row->idResponsable.'|'.$row->correo.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
        <td class="key">Email:</td>
        <td>
            <input  type="text" id="txtEmailSeguimiento" name="txtEmailSeguimiento" style="width:300px" class="cajas" value="'.$seguimiento->email.'" />
        </td>
    </tr>
	
	<tr id="filaLugar">
		<td class="key">Lugar:</td>
		<td>                       
			<input type="text" name="txtLugarEditar" id="txtLugarEditar" value="'.$seguimiento->lugar.'" 
				class="cajas" style="width:160px;" /> 
		</td>
	</tr>

	<tr id="filaCierre">
		<td class="key">Seguimiento:</td>
		<td>                           
			<input type="text" name="txtFechaCierreEditar" id="txtFechaCierreEditar" value="'.substr($seguimiento->fechaCierre,0,10).'" class="cajas" style="width:100px;" />
			
			Hora
			<input type="text" name="txtHoraCierre" id="txtHoraCierre" class="cajas" style="width:40px;" value="'.substr($seguimiento->fechaCierre,11,5).'" readonly="readonly"  />
			 
			<script>
				$("#txtFechaCierreEditar").datepicker({ changeMonth: true });
				$("#txtHoraCierre").timepicker({timeOnly: true});
			</script>
		</td>
	</tr>
	
	<tr id="filaRecordatorio">
            <td class="key">Recordatorio:</td>
            <td>                    
                       
                <select id="selectTiempo" name="selectTiempo" class="cajas" style="width:100px">
                	<option value="0">Seleccione</option>';
                  
                    foreach($tiempos as $row)
					{
						echo '<option '.($row->idTiempo==$seguimiento->idTiempo?'selected="selected"':'').' value="'.$row->idTiempo.'">'.$row->nombre.'</option>';
					}
				
                
                echo'
                </select>
                
            </td>
        </tr>
	
	<tr id="filaComentarios">
		<td class="key">Comentarios:</td>
		<td>
			<textarea id="txtComentarios" name="txtComentarios" rows="3" style="width:300px"class="TextArea">'.$seguimiento->comentarios.'</textarea>
		</td>
	</tr>

	<tr>
		<td class="key">Observaciones:</td>
		<td>
			<textarea id="txtObservacionesEditar" name="txtObservacionesEditar" rows="3" style="width:300px"class="TextArea">'.$seguimiento->comentariosExtra.'</textarea>
		</td>
	</tr>
	
</table>';