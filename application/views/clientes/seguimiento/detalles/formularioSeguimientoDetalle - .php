<?php
$nombre=$cliente->empresa;

if(strlen($cliente->nombre)>0)
	$nombre=$cliente->nombre.' '.$cliente->paterno.' '.$cliente->materno;

echo '
<form id="frmDetallesSeguimiento" name="frmDetallesSeguimiento">
	<input type="hidden" name="txtIdSeguimiento" value="'.$idSeguimiento.'" id="txtIdSeguimiento" />
	
	<div id="registrandoDetalleSeguimiento"></div>
	<table class="admintable" width="100%;">
		<tr>
			<td class="key">'.(sistemaActivo=='IEXE'?'Alumno/Cliente':'Cliente').':</td>
			<td>'.$nombre.'</td>
		</tr>
		
		<tr>
			<td class="key">Folio:</td>
			<td>'.obtenerFolioSeguimiento($seguimiento->folio).'</td>
		</tr>
	
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" name="txtFechaSeguimiento" value="'.date('Y-m-d').'" id="txtFechaSeguimiento" class="cajas" style="width:100px;" /> 
				
				Hora
				<input type="text" name="txtHoraSeguimiento" id="txtHoraSeguimiento" class="cajas" style="width:40px;" value="'.date('H:i').'" readonly="readonly"  />
				
				<script>
					$("#txtFechaSeguimiento").datepicker({ changeMonth: true });
					$("#txtHoraSeguimiento").timepicker({timeOnly: true});
				</script>
			</td>
		</tr>
		
		<tr>
            <td class="key">Responsable:</td>
            <td>
                <select id="selectResponsableDetalle" name="selectResponsableDetalle" class="cajas" style="width:300px" >';
                
                foreach($responsables as $row)
                {
                    echo '<option value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
                }
               
                echo'
				</select>
            </td>
        </tr>
	
		<tr id="filaComentarios">
			<td class="key">Observaciones:</td>
			<td>
				<textarea id="txtObservacionesSeguimiento" name="txtObservacionesSeguimiento" rows="3" style="width:300px" class="TextArea"></textarea>
			</td>
		</tr>
		
	</table>
</form>';