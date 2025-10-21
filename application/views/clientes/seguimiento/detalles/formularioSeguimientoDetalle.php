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
			<td class="key">Próximo contacto:</td>
			<td>
				<input type="text" name="txtFechaSeguimiento" value="'.date('Y-m-d').'" id="txtFechaSeguimiento" class="cajas" style="width:100px;" /> 
				
				Entre

				<select id="txtHoraCierre" name="txtHoraCierre" class="cajas" style="width:65px;" onchange="sugerirHora()" >';
				
				for($i=7;$i<=22;$i++)
				{
					for($m=0;$m<=55;$m+=5)
					{
						echo '<option>'.($i<10?'0'.$i:$i).':'.($m<10?'0'.$m:$m).'</option>';
					}
				}
					
				echo'
				</select>
				&nbsp;
				 y 
				
				<select id="txtHoraCierreFin" name="txtHoraCierreFin" class="cajas" style="width:65px;">';
				
				for($i=7;$i<=22;$i++)
				{
					for($m=0;$m<=55;$m+=5)
					{
						echo '<option>'.($i<10?'0'.$i:$i).':'.($m<10?'0'.$m:$m).'</option>';
					}
				}
				
				echo '<option>23:00</option>';
					
				echo'
				</select>

				<script>
					$("#txtFechaSeguimiento").datepicker({ changeMonth: true });
				</script>
			</td>
		</tr>
		
		<tr>
			<td class="key">Alerta:</td>
			<td align="left">       
				Crear alerta para este seguimiento <input  type="checkbox" id="chkAlertaSeguimiento" name="chkAlertaSeguimiento" value="1"  />
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