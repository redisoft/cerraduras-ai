<?php
echo'
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input type="text" name="txtNombre" id="txtNombre" class="cajas" style="width:220px;" value="'.$contacto->nombre.'" /> 
			<input type="hidden" name="txtIdContacto" id="txtIdContacto" class="cajas" style="width:220px;" value="'.$contacto->idContacto.'" />
		</td>
	</tr>
	
	
	<tr>
		<td class="key">Teléfono:</td>
		<td>
			<input placeholder="Lada" type="text" class="cajas" name="txtLadaTelefonoContactoEditar" id="txtLadaTelefonoContactoEditar" style="width:50px" value="'.$contacto->lada.'"/>
			<input type="text" name="txtTelefonoEditar" id="txtTelefonoEditar" class="cajas" style="width:140px;" value="'.$contacto->telefono.'" /> 
		</td>
	</tr>
	<tr>
		<td class="key">Extension:</td>
		<td>
			<input type="text" name="txtExtension" id="txtExtension" class="cajas"style="width:220px;" value="'.$contacto->extension.'">
		</td>
	</tr>
	
	<tr>
		<td class="key">Móvil</td>
		<td>
			<input placeholder="Lada" type="text" class="cajas" name="txtLadaMovilEditar" id="txtLadaMovilEditar" style="width:50px" value="'.$contacto->ladaMovil1.'"/>
			<input placeholder="Móvil" type="text" class="cajas" name="txtMovilEditar" id="txtMovilEditar" style="width:140px" value="'.$contacto->movil1.'"/>
			
		</td>
	</tr>
	
	<tr>
		<td class="key">Móvil 2</td>
		<td>
			<input placeholder="Lada" type="text" class="cajas" name="txtLadaMovil2Editar" id="txtLadaMovil2Editar" style="width:50px" value="'.$contacto->ladaMovil2.'"/>
			<input placeholder="Móvil 2" type="text" class="cajas" name="txtMovil2Editar" id="txtMovil2Editar" style="width:140px" value="'.$contacto->movil2.'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Nextel</td>
		<td>
			<input placeholder="Lada" type="text" class="cajas" name="txtLadaNextelEditar" id="txtLadaNextelEditar" style="width:50px" value="'.$contacto->ladaNextel.'"/>
			<input placeholder="Nextel" type="text" class="cajas" name="txtNextelEditar" id="txtNextelEditar" style="width:140px" value="'.$contacto->nextel.'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Email:</td>
		<td>
			<input type="text" name="txtEmail" id="txtEmail" class="cajas" style="width:220px;" value="'.$contacto->email.'" /> 
		</td>
	</tr>
	
	<tr>
		<td class="key">Departamento:</td>
		<td>
			<input name="txtDepartamento" style="width:220px;" type="text" class="cajas" id="txtDepartamento" value="'.$contacto->direccion.'"  /> 
		</td>
	</tr>
	
	<tr>
		<td class="key">Puesto:</td>
		<td>
			<input name="txtPuestoEditar" style="width:220px;" type="text" class="cajas" id="txtPuestoEditar" value="'.$contacto->puesto.'"  /> 
		</td>
	</tr>
</table>';