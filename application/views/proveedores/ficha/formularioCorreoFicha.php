<?php
echo'
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Asunto:</td>	
		<td>
			<input name="txtAsuntoFichaProveedor" style="width:300px" id="txtAsuntoFichaProveedor" type="text" class="cajas" value="Ficha tecnica proveedor: '.$proveedor->empresa.'" />
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
		<td class="key">Correo electrónico:</td>
		<td>
			<textarea name="txtCorreoFichaProveedor" id="txtCorreoFichaProveedor" rows="3" class="TextArea" style="width:300px">'.$proveedor->email.'</textarea>
		</td>
	</tr>	
	<tr>
		<td class="key">Mensaje</td>
		<td>
			<textarea name="txtMensajeCorreo" id="txtMensajeCorreo" rows="5" cols="40" class="TextArea" style="width:500px" ></textarea>
		</td>
	</tr>
	
	<tr>
		<td class="key">Firma: </td>
		<td>
			<textarea name="txtFirma" id="txtFirma" rows="5" class="TextArea" style="width:500px">'.$usuario->firma.'</textarea>
		</td>
	</tr>	
</table>';