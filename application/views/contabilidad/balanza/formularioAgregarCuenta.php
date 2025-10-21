<?php
echo'
<div id="registrandoInformacion"></div>
<form id="frmCuentas" name="frmCuentas">
	<table class="tablaFormularios">
		<tr>
			<td class="etiquetas">Código agrupador:</td>
			<td>
				<select id="selectCuenta" name="selectCuenta" class="selectTextosGrandes" onchange="obtenerSubCuentas()">	
					<option value="0">Seleccione cuenta</option>';
					
					foreach($cuentas as $row)
					{
						echo '<option value="'.$row->idCuenta.'-'.$row->codigo.'">'.$row->nombre.' ('.$row->cuenta.')</option>';
					}
					
				echo'
				</select><br />
				
				<input type="hidden" id="txtCodigoAgrupador" name="txtCodigoAgrupador" value="0" />
				<input type="hidden" id="txtIdCuenta" name="txtIdCuenta" value="0" />
				<input type="hidden" id="txtIdSubCuenta" name="txtIdSubCuenta" value="0" />
				
				<div id="obtenerSubCuentas">
				<select id="selectSubCuenta" name="selectSubCuenta" class="selectTextosGrandes" style="margin-top:5px" onchange="definirCodigoAgrupador()">	
					<option value="0">Seleccione subcuenta</option>
				</select>
				</div>
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Número de cuenta:</td>
			<td>
				<input type="text" class="textos" id="txtNumeroCuenta" name="txtNumeroCuenta" maxlength="100" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Descripción:</td>
			<td>
				<input type="text" class="textos" id="txtDescripcion" name="txtDescripcion" style="width:500px" maxlength="200" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Naturaleza</td>
			<td>
				<select id="selectNaturaleza" name="selectNaturaleza" class="textos">	
					<option value="A">Acreedora</option>
					<option value="D">Deudora</option>
				</select>
			</td>
		</tr>
	</table>
</form>';
