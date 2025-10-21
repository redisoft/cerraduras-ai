<?php
echo'
<form id="frmDatosFiscales">
	<input type="hidden" id="txtIdClienteFiscales" 		name="txtIdClienteFiscales" 	value="'.$cliente->idCliente.'"/>
	<table class="admintable" width="100%;">
		<tr>
			<td class="key">Razón social:</td>
			<td>
				<input type="text" class="cajas" name="txtRazonSocial" id="txtRazonSocial" style="width:200px" value="'.$cliente->razonSocial.'" />
			</td>
		</tr>
		<tr>
			<td class="key">RFC</td>
			<td>
			<input type="text" class="cajas" value="'.$cliente->rfc.'"  name="txtRfc" id="txtRfc" style="width:200px"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Calle</td>
			<td>
				<textarea class="TextArea" name="txtCalle" id="txtCalle" style="width:205px">'.$cliente->calle.'</textarea>
			</td>
		</tr>
		
		 <tr>
			<td class="key">Número</td>
			<td>
				<input type="text" class="cajas" value="'.$cliente->numero.'" name="txtNumero" id="txtNumero" style="width:200px"/>
			</td>
		</tr>
		 <tr>
			<td class="key">Colonia</td>
			<td>
				<input type="text" class="cajas"  value="'.$cliente->colonia.'" name="txtColonia" id="txtColonia" style="width:200px"/>
			</td>
		</tr>
		
		 <tr>
			<td class="key">Código Postal</td>
			<td>
				<input type="text" class="cajas"  value="'.$cliente->codigoPostal.'" name="txtCodigoPostal" id="txtCodigoPostal" style="width:200px"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Localidad</td>
			<td>
				<input type="text" class="cajas" value="'.$cliente->localidad.'" name="txtLocalidad" id="txtLocalidad" style="width:200px" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Municipio</td>
			<td>
				<input type="text" class="cajas" value="'.$cliente->municipio.'" name="txtMunicipio" id="txtMunicipio" style="width:200px" />
			</td>
		</tr>
		
		 <tr>
			<td class="key">Estado</td>
			<td>
				<input type="text" class="cajas" value="'.$cliente->estado.'" name="txtEstado" id="txtEstado" style="width:200px"/>
			</td>
		</tr>
		<tr>
			<td class="key">País</td>
			<td>
				<input type="text" class="cajas" value="'.$cliente->pais.'" name="txtPais" id="txtPais" style="width:200px"/>
			</td>
		</tr>
	</table>
</form>';