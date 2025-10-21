<?php
echo '
<form id="frmDireccion">
	<table class="admintable" width="100%;">
		<tr>
			<td class="key">RFC:</td>
			<td>
				<input name="txtRfc" id="txtRfc" type="text" class="cajas" style="width:200px; " maxlength="15"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Empresa:</td>
			<td>
				<input name="txtEmpresa" id="txtEmpresa" type="text" class="cajas" style="width:400px" maxlength="1000"  />
			</td>
		</tr>
		
		<tr>
			<td class="key">Calle:</td>
			<td>
				<input name="txtCalle" id="txtCalle" type="text" class="cajas" style="width:200px; " maxlength="100"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Número:</td>
			<td>
				<input name="txtNumero" id="txtNumero" type="text" class="cajas" style="width:200px; " maxlength="50"/>
			</td>
		</tr>
		<tr>
			<td class="key">Colonia:</td>
			<td>
				<input name="txtColonia" id="txtColonia" type="text" class="cajas" style="width:200px; " maxlength="70"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Localidad:</td>
			<td>
				<input name="txtLocalidad" id="txtLocalidad" type="text" class="cajas" style="width:200px; " maxlength="70"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Municipio:</td>
			<td>
				<input name="txtMunicipio" id="txtMunicipio" type="text" class="cajas" style="width:200px; " maxlength="50"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Estado:</td>
			<td>
				<input name="txtEstado" id="txtEstado" type="text" class="cajas" style="width:200px; " maxlength="40"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">País:</td>
			<td>
				<input name="txtPais" id="txtPais" type="text" class="cajas" style="width:200px; " maxlength="100"/>
			</td>
		</tr>
		
		
		<tr>
			<td class="key">Código postal:</td>
			<td>
				<input name="txtCodigoPostal" id="txtCodigoPostal" type="text" class="cajas" style="width:200px; " maxlength="5"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Régimen fiscal:</td>
			<td>
				<select class="cajas" id="selectRegimenFiscal" name="selectRegimenFiscal" style="width:500px">';

					foreach($regimen as $row)
					{
						echo '<option value="'.$row->idRegimen.'">'.$row->clave.', '.$row->nombre.'</option>';
					}

				echo'
				</select>

			</td>
		</tr>
		
		<tr>
			<td class="key">Teléfono:</td>
			<td>
				<input name="txtTelefono" id="txtTelefono" type="text" class="cajas" style="width:200px; " maxlength="100"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Email:</td>
			<td>
				<input name="txtEmail" id="txtEmail" type="text" class="cajas" style="width:200px; " maxlength="100"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Tipo de dirección:</td>
			<td>
				<select id="selectTipoDireccion" name="selectTipoDireccion" class="cajas" style="width:200px">
					<option value="0">Envío</option>
					<option value="1">Fiscal</option>
					<option value="2">Envío y fiscal</option>
				</select>
			</td>
		</tr>
		
	</table>
</form>';
