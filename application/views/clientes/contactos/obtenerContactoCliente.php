<?php
echo'
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			'.$cliente->nombre.' '.$cliente->paterno.' '.$cliente->materno.'
			<input type="hidden" name="txtIdClienteContacto" id="txtIdClienteContacto" class="cajas" style="width:220px;" value="'.$cliente->idCliente.'" />
		</td>
	</tr>
	
	
	<tr>
		<td class="key">Teléfono:</td>
		<td>
			<input placeholder="Lada" type="text" class="cajas" name="txtLadaTelefonoContactoEditar" id="txtLadaTelefonoContactoEditar" style="width:50px" value="'.$cliente->lada.'"/>
			<input type="text" name="txtTelefonoEditar" id="txtTelefonoEditar" class="cajas" style="width:140px;" value="'.$cliente->telefono.'" /> 
		</td>
	</tr>

	<tr>
		<td class="key">Móvil</td>
		<td>
			<input placeholder="Lada" type="text" class="cajas" name="txtLadaMovilEditar" id="txtLadaMovilEditar" style="width:50px" value="'.$cliente->ladaMovil.'"/>
			<input placeholder="Móvil" type="text" class="cajas" name="txtMovilEditar" id="txtMovilEditar" style="width:140px" value="'.$cliente->movil.'"/>
			
		</td>
	</tr>
	

	<tr>
		<td class="key">Email:</td>
		<td>
			<input type="text" name="txtEmail" id="txtEmail" class="cajas" style="width:220px;" value="'.$cliente->email.'" /> 
		</td>
	</tr>
</table>';