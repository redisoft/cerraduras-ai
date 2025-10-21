<?php
echo'
<table class="admintable" width="99%;">
	<tr>
	<td class="key">Nombre:</td>
	<td>
		<input type="text" name="txtNombre" id="txtNombre" class="cajas" style="width:220px;" value="'.$contacto->nombre.'" /> 
		<input type="hidden" name="txtIdContacto" id="txtIdContacto" class="cajas" style="width:220px;" value="'.$contacto->idContacto.'" />
	</td>
	</tr>
	<tr>
		<td class="key">Departamento:</td>
		<td>
			<input name="txtDepartamento" style="width:220px;" type="text" class="cajas" id="txtDepartamento" value="'.$contacto->departamento.'"  /> 
		</td>
	</tr>
	<tr>
		<td class="key">Teléfono:</td>
		<td>
			<input type="text" name="txtTelefono" id="txtTelefono" class="cajas" style="width:220px;" value="'.$contacto->telefono.'" /> 
		</td>
	</tr>
	
	<tr>
	  <td class="key">Extension:</td>
	  <td>
		<input type="text" name="txtExtension" id="txtExtension" class="cajas"style="width:220px;" value="'.$contacto->extension.'">
	</td>
	</tr>
	
	<tr>
		<td class="key">Email:</td>
		<td>
			<input type="text" name="txtEmail" id="txtEmail" class="cajas" style="width:220px;" value="'.$contacto->email.'" /> 
		</td>
	</tr>
</table>';