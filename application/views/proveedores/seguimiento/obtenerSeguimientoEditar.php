<script>
$(document).ready(function()
{
	$('#txtFechaSeguimiento').datepicker();
	$('#txtFechaCierre').datepicker()
	
	$("#txtHoraSeguimiento, #txtHoraCierre").timepicker({timeOnly: true});
	
	$("#txtBuscarCompraCrm").autocomplete(
	{
		source:base_url+'configuracion/obtenerOrdenesCompra/3/<?php echo $seguimiento->idProveedor?>',
		
		select:function( event, ui)
		{
			$('#txtIdCompraCrm').val(ui.item.idCompras);
		}
	});
	
	opcionesSeguimiento();
});

</script>
<?php
echo '
<input type="hidden" name="txtIdSeguimiento" value="'.$idSeguimiento.'" id="txtIdSeguimiento" />
<input type="hidden" name="txtIdProveedor" value="'.$seguimiento->idProveedor.'" id="txtIdCliente" /> ';
echo'
<div id="enviandoBitacora"></div>
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Proveedor:</td>
		<td>'.$proveedor->empresa.'</td>
	</tr>
	<tr>
		<td class="key">Folio:</td>
		<td>'.obtenerFolioSeguimiento($seguimiento->folio).'</td>
	</tr>
	<tr>
		<td class="key">CRM:</td>
		<td>
			<select id="selectStatus" name="selectStatus" class="cajas" onchange="opcionesSeguimiento()">';
			
			foreach($status as $row)
			{
				$seleccionado=$row->idStatus==$seguimiento->idStatus?'selected="selected"':'';
				
				#echo '<option '.$seleccionado.' value="'.$row->idStatus.'">'.$row->nombre.'</option>';
				echo '<option '.($row->idStatus==$seguimiento->idStatus?'selected="selected"':'').' value="'.$row->idStatus.'|'.$row->idStatusIgual.'">'.$row->nombre.'</option>';
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
		<td>
			<select id="selectServicio" name="selectServicio" class="cajas" onchange="opcionesServicios()">';
			
			foreach($servicios as $row)
			{
				$seleccionado=$row->idServicio==$seguimiento->idServicio?'selected="selected"':'';
				
				echo '<option '.$seleccionado.' value="'.$row->idServicio.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
			
			<input type="text" name="txtBuscarCompraCrm" id="txtBuscarCompraCrm" placeholder="Seleccione venta" class="cajas" style="width:300px; '.($seguimiento->idServicio!=3?'display:none':'').'" value="'.$seguimiento->compra.'"/>
			
			<input type="hidden" name="txtIdCompraCrm" id="txtIdCompraCrm" value="'.$seguimiento->idCompra.'" />
		</td>
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
				echo '<option '.($row->idResponsable==$seguimiento->idResponsable?'selected="selected"':'').' value="'.$row->idResponsable.'|'.$row->correo.'">'.$row->nombre.'</option>';
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
	
	 <!--<tr id="filaMonto">
		<td class="key">Monto:</td>
		<td>                       
			<input type="text" name="txtMontoEditar" id="txtMontoEditar" value="'.$seguimiento->monto.'" class="cajas" style="width:160px;" /> 
		</td>
	</tr>-->
	
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
	
	<tr id="filaBitacora" style="display:none">
        <td class="key">Bitácora:</td>
        <td>
            <textarea id="txtBitacora" name="txtBitacora" rows="3" style="width:300px"class="TextArea">'.$seguimiento->bitacora.'</textarea>
        </td>
    </tr>
	
	<tr id="filaEnviarBitacora" style="display:none">
		<td class="key">Enviar:</td>
		<td>
			<img src="'.base_url().'img/correo.png" title="Enviar" onclick="enviarBitacoraEditar()" width="24" height="22" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Observaciones:</td>
		<td>
			<textarea id="txtObservacionesEditar" name="txtObservacionesEditar" rows="3" style="width:300px"class="TextArea">'.$seguimiento->comentariosExtra.'</textarea>
		</td>
	</tr>
	
</table>';