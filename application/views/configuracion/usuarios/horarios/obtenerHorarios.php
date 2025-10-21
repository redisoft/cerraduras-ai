<script>
$(document).ready(function()
{
	$("#txtHoraInicial,#txtHoraFinal").timepicker({timeOnly: true});
});
</script>

<?php
echo '
<form id="frmHorario" name="frmHorario">
	<table width="100%" class="admintable" id="tablaHorarios">
		<tr>
			<th colspan="2" class="titulos">Detalles de usuario</th>
		</tr>
		
		<tr>
			<td class="key">Usuario:</td>
			<td>
				'.$usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno.'
				<input type="hidden" id="txtIdUsuario" name="txtIdUsuario" value="'.$usuario->idUsuario.'" />
			</td>
		</tr>
	</table>';
	
	echo '
	<table width="100%" class="admintable" id="tablaHorarios">
		<tr>
			<th colspan="11" class="titulos">Registro</th>
		</tr>
		
		<tr>
			<th class="datos">Lunes</th>
			<th class="datos">Martes</th>
			<th class="datos">Miércoles</th>
			<th class="datos">Jueves</th>
			<th class="datos">Viernes</th>
			<th class="datos">Sábado</th>
			<th class="datos">Domingo</th>
			<th class="datos">Hora inicial</th>
			<th class="datos">Hora final</th>
		</tr>
		<tr>
			<td align="center"><input type="checkbox" id="chkLunes" name="chkLunes" value="1" /></td>
			<td align="center"><input type="checkbox" id="chkMartes" name="chkMartes" value="1" /></td>
			<td align="center"><input type="checkbox" id="chkMiercoles" name="chkMiercoles" value="1" /></td>
			<td align="center"><input type="checkbox" id="chkJueves" name="chkJueves" value="1" /></td>
			<td align="center"><input type="checkbox" id="chkViernes" name="chkViernes" value="1" /></td>
			<td align="center"><input type="checkbox" id="chkSabado" name="chkSabado" value="1" /></td>
			<td align="center"><input type="checkbox" id="chkDomingo" name="chkDomingo" value="1" /></td>
			<td align="center"><input type="text" class="cajas" style="width:50px" id="txtHoraInicial" name="txtHoraInicial" value="'.date('H:i').'" readonly="readonly" /></td>
			<td align="center"><input type="text" class="cajas" style="width:50px" id="txtHoraFinal" name="txtHoraFinal" value="'.date('H:i').'" readonly="readonly" /></td>
		</tr>
	</table>
</form>';
if($horarios!=null)
{
	echo'
	
	<script>
	$(document).ready(function()
	{
		$("#tablaHorarios tr:even").addClass("arriba");
		$("#tablaHorarios tr:odd").addClass("abajo");  
	});
	</script>

	<table width="100%" class="admintable" id="tablaHorarios">
		<tr>
			<th colspan="11" class="titulos">Lista de horarios</th>
		</tr>
		
		<tr>
			<th class="datos">#</th>
			<th class="datos">Lunes</th>
			<th class="datos">Martes</th>
			<th class="datos">Miércoles</th>
			<th class="datos">Jueves</th>
			<th class="datos">Viernes</th>
			<th class="datos">Sábado</th>
			<th class="datos">Domingo</th>
			<th class="datos">Hora inicial</th>
			<th class="datos">Hora final</th>
			<th class="datos">Acciones</th>
		</tr>';
		
		$i=1;
		foreach($horarios as $row)
		{
			echo '
			<tr id="filaHorario'.$row->idHorario.'">
				<td align="right" width="5%">'.$i.'</td>
				<td align="center"><input type="checkbox" id="chkLunes'.$i.'" name="chkLunes'.$i.'" value="1" '.($row->lunes=='1'?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" id="chkMartes'.$i.'" name="chkMartes'.$i.'" value="1" '.($row->martes=='1'?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" id="chkMiercoles'.$i.'" name="chkMiercoles'.$i.'" value="1" '.($row->miercoles=='1'?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" id="chkJueves'.$i.'" name="chkJueves'.$i.'" value="1" '.($row->jueves=='1'?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" id="chkViernes'.$i.'" name="chkViernes'.$i.'" value="1" '.($row->viernes=='1'?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" id="chkSabado'.$i.'" name="chkSabado'.$i.'" value="1" '.($row->sabado=='1'?'checked="checked"':'').' /></td>
				<td align="center"><input type="checkbox" id="chkDomingo'.$i.'" name="chkDomingo'.$i.'" value="1" '.($row->domingo=='1'?'checked="checked"':'').' /></td>
				
				<td align="center">
					<input type="text" class="cajas" style="width:50px" id="txtHoraInicial'.$i.'" name="txtHoraInicial'.$i.'" value="'.substr($row->horaInicial,0,5).'" readonly="readonly" />
				</td>
				<td align="center">
					<input type="text" class="cajas" style="width:50px" id="txtHoraFinal'.$i.'" name="txtHoraFinal'.$i.'" value="'.substr($row->horaFinal,0,5).'" readonly="readonly" />
				</td>
				<script>
				$(document).ready(function()
				{
					$("#txtHoraInicial'.$i.',#txtHoraFinal'.$i.'").timepicker({timeOnly: true});
				});
				</script>';
				
				echo'
				<td class="vinculos" align="center" width="20%">
				
					<img id="btnEditarHorario'.$i.'" src="'.base_url().'img/editar.png" title="Editar rol" onclick="editarHorario('.$row->idHorario.','.$i.')" />
					&nbsp;&nbsp;	
					<img id="btnBorrarHorario'.$i.'" src="'.base_url().'img/borrar.png" title="Borrar rol" onclick="borrarHorario('.$row->idHorario.')" />
					<br />
					<a id="a-btnEditarHorario'.$i.'">Editar</a>
					<a id="a-btnBorrarHorario'.$i.'">Borrar</a>';
					
					if($permiso[2]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnEditarHorario'.$i.'\');
						</script>';
					}
					
					if($permiso[3]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnBorrarHorario'.$i.'\');
						</script>';
					}

				echo'
				</td>';
				
			echo'
			</tr>';
			
			$i++;
		}
		
	echo'
	</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de horarios</div>';
}

?>