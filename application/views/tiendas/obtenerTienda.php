<?php

echo '
<form id="frmTiendas" name="frmTiendas">
	<table class="admintable" width="100%">	
		<tr>
			<td class="key">Nombre: </td>
			<td>
				<input type="text" class="cajas" id="txtNombre" name="txtNombre" style="width:250px" value="'.$tienda->nombre.'" />
				<input type="hidden" id="txtIdTienda" name="txtIdTienda" value="'.$tienda->idTienda.'" />
			</td>
		</tr>
		<tr>
			<td class="key">Calle: </td>
			<td>
				<input type="text" class="cajas" id="txtCalle" name="txtCalle" style="width:250px" value="'.$tienda->calle.'"/>
			</td>
		</tr>
		<tr>
			<td class="key">Número: </td>
			<td>
				<input type="text" class="cajas" id="txtNumero" name="txtNumero" style="width:250px" value="'.$tienda->numero.'"/>
			</td>
		</tr>
		<tr>
			<td class="key">Colonia: </td>
			<td>
				<input type="text" class="cajas" id="txtColonia" name="txtColonia" style="width:250px" value="'.$tienda->colonia.'"/>
			</td>
		</tr>
		<tr>
			<td class="key">Localidad: </td>
			<td>
				<input type="text" class="cajas" id="txtLocalidad" name="txtLocalidad" style="width:250px" value="'.$tienda->localidad.'"/>
			</td>
		</tr>
		<tr>
			<td class="key">Municipio: </td>
			<td>
				<input type="text" class="cajas" id="txtMunicipio" name="txtMunicipio" style="width:250px" value="'.$tienda->municipio.'"/>
			</td>
		</tr>
		<tr>
			<td class="key">Estado: </td>
			<td>
				<input type="text" class="cajas" id="txtEstado" name="txtEstado" style="width:250px" value="'.$tienda->estado.'"/>
			</td>
		</tr>
		<tr>
			<td class="key">Código postal: </td>
			<td>
				<input type="text" class="cajas" id="txtCodigoPostal" name="txtCodigoPostal" style="width:250px" value="'.$tienda->codigoPostal.'"/>
			</td>
		</tr>
		<tr>
			<td class="key">Teléfono: </td>
			<td>
				<input type="text" class="cajas" id="txtTelefono" name="txtTelefono" style="width:250px" value="'.$tienda->telefono.'"/>
			</td>
		</tr>
		<tr>
			<td class="key">Email: </td>
			<td>
				<input type="text" class="cajas" id="txtEmail" name="txtEmail" style="width:250px" value="'.$tienda->email.'"/>
			</td>
		</tr>
	</table>
</form>';
	
	