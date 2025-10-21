<?php
echo'
<div id="registrandoInformacion"></div>
<form id="frmCuentas" name="frmCuentas">
	<table class="tablaFormularios">
		<tr>
			<td class="etiquetas">Código agrupador:</td>
			<td>
				'.($cuenta->idSubCuenta==0?$cuenta->cuenta:$cuenta->subCuenta).'('.$cuenta->codigoAgrupador.')
				<input type="hidden" id="txtIdDetalle" name="txtIdDetalle" value="'.$cuenta->idDetalle.'" />
				
			
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Número de cuenta:</td>
			<td>
				<input type="text" class="textos" id="txtNumeroCuenta" name="txtNumeroCuenta" maxlength="100" value="'.$cuenta->numeroCuenta.'" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Descripción:</td>
			<td>
				<input type="text" class="textos" id="txtDescripcion" name="txtDescripcion" style="width:500px" maxlength="200" value="'.$cuenta->descripcion.'" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Naturaleza</td>
			<td>
				<select id="selectNaturaleza" name="selectNaturaleza" class="textos">	
					<option value="A">Acreedora</option>
					<option '.($cuenta->naturaleza=='D'?'selected="selected"':'').' value="D">Deudora</option>
				</select>
			</td>
		</tr>
	</table>
</form>';
