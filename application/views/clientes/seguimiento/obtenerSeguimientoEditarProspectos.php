<script>

$(document).ready(function()
{
	$("#txtBuscarCotizacionCrm").autocomplete(
	{
		source:base_url+'configuracion/obtenerListaCotizaciones/<?php echo $seguimiento->idCliente?>',
		
		select:function( event, ui)
		{
			$('#txtIdCotizacionCrm').val(ui.item.idCotizacion);
		}
	});
	
	$("#txtBuscarVentaCrm").autocomplete(
	{
		source:base_url+'configuracion/obtenerListaVentas/<?php echo $seguimiento->idCliente?>',
		
		select:function( event, ui)
		{
			$('#txtIdVentaCrm').val(ui.item.idCotizacion);
		}
	});
	
	opcionesSeguimiento();
});

	//opcionesServicios();
</script>


<?php
$empresa	= $cliente->empresa;

if(sistemaActivo=='IEXE')
{
	if(strlen($cliente->nombre)>0)
	{
		$empresa	= $cliente->nombre.' '.$cliente->paterno.' '.$cliente->materno;
	}
}


echo '
<input type="hidden" name="txtIdSeguimiento" value="'.$idSeguimiento.'" id="txtIdSeguimiento" />';
echo'
<div id="enviandoBitacora"></div>
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Prospecto:</td>
		<td>'.$empresa.'</td>
	</tr>
	
	<tr>
		<td class="key">Folio:</td>
		<td>'.obtenerFolioSeguimiento($seguimiento->folio).'</td>
	</tr>';
	
	if(sistemaActivo=='IEXE')
	{
		echo'
		<tr>
			<td class="key">Promotor:</td>
			<td>
				<select id="selectUsuarioRegistro" name="selectUsuarioRegistro" class="cajas"  style="width:300px">';
				
				foreach($responsablesRegistro as $row)
				{
					echo '<option '.($row->idResponsable==$seguimiento->idUsuarioRegistro?'selected="selected"':'').' value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
				}
				
				echo'
				</select>
			</td>
		</tr>';
	}
	
	echo'
	<tr>
		<td class="key">CRM:</td>
		<td>
			<select id="selectStatus" name="selectStatus" class="cajas" onchange="opcionesSeguimiento()">';
			
			foreach($status as $row)
			{
				echo '<option '.($row->idStatus==$seguimiento->idStatus?'selected="selected"':'').' value="'.$row->idStatus.'|'.$row->idStatusIgual.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Estatus:</td>
		<td>
			<select id="selectEstatus" name="selectEstatus" class="cajas">';
			
			foreach($estatus as $row)
			{
				echo '<option '.($row->idEstatus==$seguimiento->idEstatus?'selected="selected"':'').' value="'.$row->idEstatus.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha:</td>
		<td>';
			
			 if($idRol!=1) echo substr($seguimiento->fecha,0,15);
			 
			echo'
			<input type="text" name="txtFechaEditar" value="'.substr($seguimiento->fecha,0,10).'" id="txtFechaEditar" class="cajas" style="width:100px; '.($idRol!=1?'display:none':'').'" /> 
			
			'.($idRol==1?'Hora':'').'
			<input type="text" name="txtHoraSeguimiento" id="txtHoraSeguimiento" class="cajas" style="width:40px; '.($idRol!=1?'display:none':'').'" value="'.substr($seguimiento->fecha,11,5).'" readonly="readonly"  />
			
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
				echo '<option '.($row->idServicio==$seguimiento->idServicio?'selected="selected"':'').' value="'.$row->idServicio.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
			
			<input type="text" name="txtBuscarCotizacionCrm" id="txtBuscarCotizacionCrm" placeholder="Seleccione cotización" class="cajas" style="width:300px; '.($seguimiento->idServicio!=1?'display:none':'').'" value="'.$seguimiento->cotizacion.'"/>
            <input type="text" name="txtBuscarVentaCrm" id="txtBuscarVentaCrm" placeholder="Seleccione venta" class="cajas" style="width:300px; '.($seguimiento->idServicio!=2?'display:none':'').'" value="'.$seguimiento->venta.'"/>
			
			<input type="hidden" name="txtIdCotizacionCrm" id="txtIdCotizacionCrm" value="'.$seguimiento->idCotizacion.'" />
			<input type="hidden" name="txtIdVentaCrm" 	id="txtIdVentaCrm" 	value="'.$seguimiento->idVenta.'" />
			
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
	
	<tr style="display:none">
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
	
	<tr style="display:none">
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
		<td class="key">Próximo contacto:</td>
		<td>                           
			<input type="text" name="txtFechaCierreEditar" id="txtFechaCierreEditar" value="'.substr($seguimiento->fechaCierre,0,10).'" class="cajas" style="width:100px;" />';

			echo '
			<select id="txtHoraCierre" name="txtHoraCierre" class="cajas" style="width:65px;">';
				
			for($i=7;$i<=22;$i++)
			{
				echo '<option '.(substr($seguimiento->fechaCierre,11,5)==($i<10?'0'.$i:$i).':00'?'selected="selected"':'').'>'.($i<10?'0'.$i:$i).':00</option>';
				echo '<option '.(substr($seguimiento->fechaCierre,11,5)==($i<10?'0'.$i:$i).':30'?'selected="selected"':'').'>'.($i<10?'0'.$i:$i).':30</option>';
			}
				
			echo'
			</select>
			&nbsp;
			 y 
			
			<select id="txtHoraCierreFin" name="txtHoraCierreFin" class="cajas" style="width:65px;">';
			
			for($i=7;$i<=22;$i++)
			{
				echo '<option '.(substr($seguimiento->horaCierreFin,0,5)==($i<10?'0'.$i:$i).':00'?'selected="selected"':'').'>'.($i<10?'0'.$i:$i).':00</option>';
				echo '<option '.(substr($seguimiento->horaCierreFin,0,5)==($i<10?'0'.$i:$i).':30'?'selected="selected"':'').'>'.($i<10?'0'.$i:$i).':30</option>';
			}
				
			echo'
			</select>';
			
			
			echo'
			<!--&nbsp;
             Entre
			<input type="text" name="txtHoraCierre" id="txtHoraCierre" class="cajas" style="width:40px;" value="'.substr($seguimiento->fechaCierre,11,5).'" readonly="readonly"  />
			
			&nbsp;
			 y 
			<input type="text" name="txtHoraCierreFin" id="txtHoraCierreFin" class="cajas" style="width:40px;" value="'.substr($seguimiento->horaCierreFin,0,5).'" readonly="readonly"  />-->
			 
			<script>
				$("#txtFechaCierreEditar").datepicker({ changeMonth: true });
				$("#txtHoraCierre, #txtHoraCierreFin").timepicker({timeOnly: true});
			</script>
		</td>
	</tr>
	
	<tr>
		<td class="key">Alerta:</td>
		<td>  
			Crear alerta para este seguimiento <input  type="checkbox" id="chkAlertaSeguimiento" name="chkAlertaSeguimiento" value="1" '.($seguimiento->alerta=='1'?'checked="checked"':'').' />
			
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