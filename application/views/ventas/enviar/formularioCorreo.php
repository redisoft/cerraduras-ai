<?php
echo'
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Asunto:</td>	
		<td>
			<input name="asunto" style="width:300px" id="asunto" type="text" class="cajas"  value="Orden de venta '.$serie.'" />
				
			<input type="hidden" id="txtIdCotizacion" value="'.$this->input->post('idCotizacion').'" />
		</td>
	</tr>';
	
	if($this->session->userdata('rol')!='1')
	{
		echo'
		<tr>
			<td class="key">Usuario:</td>	
			<td>'.($usuario->nombre.' '.$usuario->apellidoPaterno.' '.$usuario->apellidoMaterno).'('.$usuario->correo.')
			<input type="hidden" id="selectUsuariosEnviar" value="'.$usuario->idUsuario.'" /></td>
		</tr>';
	}
	
	if($this->session->userdata('rol')=='1')
	{
		echo'
		<tr>
			<td class="key">Usuario:</td>
			<td>
				<select class="cajas" id="selectUsuariosEnviar" name="selectUsuariosEnviar" style="width:400px" onchange="sugerirFirma(this.value)">';
		
				foreach($usuarios as $row)
				{
					echo '<option '.($row->idUsuario==$usuario->idUsuario?'selected="selected"':'').' value="'.$row->idUsuario.'">'.$row->nombre.'('.$row->correo.')</option>';	
				}
		
				echo '
				</select>';
				
				foreach($usuarios as $row)
				{
					echo '<input type="hidden" id="txtFirma'.$row->idUsuario.'" name="txtFirma'.$row->idUsuario.'" value="'.$row->firma.'" />';
				}
				
				echo'
			</td>
		</tr>';
	}
	
	echo'
	<tr>
		<td class="key">Cliente:</td>	
		<td>'.$cotizacion->empresa.'</td>
	</tr>
	
	<tr>
		<td class="key">Contacto:</td>	
		<td>
			<select id="selectContactoEnviar" id="selectContactoEnviar" class="cajas" style="width:300px" onchange="sugerirEmailContacto(this.value)">';
			
			foreach($contactos as $row)
			{
				echo '<option value="'.$row->email.'">'.$row->nombre.'</option>';
			}
			
			echo'
			</select>
		</td>
	</tr>	
	
	<tr>
		<td class="key">Correo electronico</td>
		<td>
			<textarea name="correo" id="correo" rows="3" class="TextArea" style="width:300px">'.(isset($contactos[0]->email)!=null?$contactos[0]->email:$cotizacion->email).'</textarea>
		</td>
	</tr>	
	<tr>
		<td class="key">Mensaje</td>
		<td>
			<textarea name="mensa" id="mensa" rows="5" cols="40" class="TextArea" style="width:500px" ></textarea>
		</td>
	</tr>
	
	<tr>
		<td class="key">Firma: </td>
		<td>
			<textarea name="txtFirma" id="txtFirma" rows="5" class="TextArea" style="width:500px">'.$usuario->firma.'</textarea>
		</td>
	</tr>	
</table>';

if($historial!=null)
{
	$i=1;
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="4" class="encabezadoPrincipal">Historial de envíos</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Correo</th>
			<th>Usuario</th>
		</tr>';
	
	foreach($historial as $row)
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td>'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td>'.$row->correo.'</td>
			<td>'.$row->usuario.'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}