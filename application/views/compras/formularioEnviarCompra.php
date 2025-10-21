<?php
echo '
<form id="frmEnviarCompra" name="frmEnviarCompra">
	<div class="ui-state-error" ></div>
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Compra:</td>
			<td>'.$compra->nombre.'</td>
		</tr>
		<tr>
			<td class="key">Proveedor:</td>
			<td>'.$compra->empresa.'</td>
		</tr>
		<tr>
			<td class="key">Total:</td>
			<td>'.number_format($compra->total,2).'</td>
		</tr>
		<tr>
			<td class="key">Asunto:</td>	
			<td>
				<input name="txtAsunto" id="txtAsunto" type="text" style="width:300px" class="cajas" value="Compra: '.$compra->nombre.'" />
				<input name="txtIdCompra" id="txtIdCompra" type="hidden" value="'.$compra->idCompras.'" />
			</td>
		</tr>	
		
		
		 <tr>
			<td class="key">Correo</td>
			<td><input type="text" class="cajas" id="txtCorreo" name="txtCorreo" style="width:300px" value="'.$compra->email.'" /></td>
		</tr>
		<tr>
			<td class="key">Contactos:</td>
			<td>
				<input type="hidden" id="txtNumeroContactos" name="txtNumeroContactos" value="'.count($contactos).'" />';
			
				$i=0;
				foreach($contactos as $row)
				{
					if(strlen($row->email)>4)
					{
						echo '<input type="checkbox" id="chkContacto'.$i.'" name="chkContacto'.$i.'" value="1" title="Seleccionar" />
						<input type="hidden" id="txtEmailContacto'.$i.'" name="txtEmailContacto'.$i.'" value="'.$row->email.'" />';
						echo ' <a>Contacto:</a> '.$row->nombre.', <a>Email:</a> '.$row->email.'<br />';
					}
					
					$i++;
				}
				
			echo'
			</td>
		</tr>	
		
		<tr>
			<td class="key">Mensaje</td>
			<td>
				<textarea class="TextArea" rows="5" style="width:500px" id="txtMensaje" name="txtMensaje"></textarea>
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
			<td class="key">Firma: </td>
			<td>
				<textarea name="txtFirma" id="txtFirma" rows="5" class="TextArea" style="width:500px">'.$usuario->firma.'</textarea>
			</td>
		</tr>
	</table>
</form>';
