<?php
echo'
<form id="frmClientes">
	
	<table class="admintable" width="100%;">
		<tr> 	 
				<td class="key"># Cliente:</td>
				<td>
					<input type="text" class="cajas" name="txtAlias" id="txtAlias" style="width:200px" value="'.$numeroCliente.'" />
				</td>
			</tr>
		<tr>
			<td class="key">Empresa:</td>
			<td>
				<input type="text" class="cajas" id="txtEmpresa" name="txtEmpresa" style="width:500px" />
			</td>
		</tr>

		<tr>
			<td class="key">Razón social:</td>
			<td>
				<input type="text" class="cajas" name="txtRazonSocial" id="txtRazonSocial" style="width:500px" />
			</td>
		</tr>

		<tr>
			<td class="key">RFC:</td>
			<td>
				<input type="text" class="cajas" name="txtRfc" id="txtRfc" style="width:200px" />
			</td>
		</tr>
		<tr>
			<td class="key">Calle:</td>
			<td>
				<textarea class="TextArea" name="txtCalle" id="txtCalle" style="width:200px"></textarea>
			</td>
		</tr>

		 <tr>
			<td class="key">Número:</td>
			<td>
				<input type="text" class="cajas" name="txtNumero" id="txtNumero" style="width:200px"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Número interior:</td>
			<td>
				<input type="text" class="cajas" name="txtNumeroInterior" id="txtNumeroInterior" style="width:200px"/>
			</td>
		</tr>

		 <tr>
			<td class="key">Colonia:</td>
			<td>
				<input type="text" class="cajas" name="txtColonia" id="txtColonia" style="width:200px"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Localidad:</td>
			<td>
				<input type="text" class="cajas" name="txtLocalidad" id="txtLocalidad" style="width:200px" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Municipio:</td>
			<td>
				<input type="text" class="cajas" name="txtMunicipio" id="txtMunicipio" style="width:200px" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Estado:</td>
			<td>
				<input type="text" class="cajas" name="txtEstado" id="txtEstado" style="width:200px"/>
			</td>
		</tr>

		<tr>
			<td class="key">País:</td>
			<td>
				<input type="text" class="cajas" name="txtPais" id="txtPais" style="width:200px"/>
			</td>
		</tr>

		<tr>
			<td class="key">Código Postal:</td>
			<td>
				<input type="text" class="cajas" name="txtCodigoPostal" id="txtCodigoPostal" style="width:200px"/>
			</td>
		</tr>

		<tr>
			<td class="key">Teléfono:</td>
			<td>
				<input type="text" class="cajas" name="txtTelefono" id="txtTelefono" style="width:200px" /><br />
			</td>
		</tr>
		<tr>
			<td class="key">Email:</td>
			<td>
				<input type="text" class="cajas" name="txtEmail" id="txtEmail" style="width:200px" /><br />
			</td>
		</tr>
		
		<tr '.(!$clienteSucursal?'style="display: none"':'').'>
			<td class="key">Sucursal traspasos:</td>
			<td>
				<select class="cajas" id="selectSucursal" name="selectSucursal" style="width:200px">
					<option value="0">Seleccione</option>';

					foreach($licencias as $row)
					{
						if($row->idLicencia!=$idLicencia)
						{
							echo '<option value="'.$row->idLicencia.'">'.$row->nombre.'</option>';
						}
					}

				echo'
				</select>

			</td>
		</tr>
	</table>
</form>';