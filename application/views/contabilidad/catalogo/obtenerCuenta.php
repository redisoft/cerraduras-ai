<?php
echo'
<script>
$(document).ready(function()
{
	$("#tablaEditarCuenta tr:even").addClass("abajo");
	$("#tablaEditarCuenta tr:odd").addClass("arriba");  
	
	$("#txtFechaCuenta").datepicker();
});
</script>
<div id="registrandoInformacion"></div>
<form id="frmCuentas" name="frmCuentas">
	<table class="admintable" id="tablaEditarCuenta" width="100%">
		<tr>
			<td class="key">Cuenta SAT:</td>
			<td>';
				
				$codigo	= $this->contabilidad->obtenerCuentaNivel($cuenta->nivel,$cuenta->idCuenta);
				
				echo $codigo!=null?$codigo->nombre.'('.$codigo->codigo.')':'';
				echo'
				<input type="hidden" id="txtIdDetalle" name="txtIdDetalle" value="'.$cuenta->idCuentaCatalogo.'" />
				
			
			</td>
		</tr>
		
		<tr>
			<td class="key">Naturaleza</td>
			<td>
				<select id="selectNaturaleza" name="selectNaturaleza" class="cajas">	
					<option '.($cuenta->naturaleza=='A'?'selected="selected"':'style="display:none"').' value="A">Acreedora</option>
					<option '.($cuenta->naturaleza=='D'?'selected="selected"':'style="display:none"').' value="D">Deudora</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="key">Referencia contable	:</td>
			<td>
				<input type="text" class="cajas" id="txtNumeroCuenta" name="txtNumeroCuenta" maxlength="100" value="'.$cuenta->numeroCuenta.'" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Descripción:</td>
			<td>
				<input type="text" class="cajas" id="txtDescripcion" name="txtDescripcion" style="width:500px" maxlength="200" value="'.$cuenta->descripcion.'" />
			</td>
		</tr>
		
		<tr style="display:none">
			<td class="key">Subcuenta:</td>
			<td>
				<input type="text" class="cajas" id="txtSubCuenta" name="txtSubCuenta" value="'.$cuenta->subCuenta.'" maxlength="100" />
			</td>
		</tr>
		
		
		
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" class="cajas" id="txtFechaCuenta" name="txtFechaCuenta" value="'.$cuenta->fecha.'" readonly="readonly" style="width:100px" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Saldo:</td>
			<td>
				<input type="text" class="cajas" id="txtSaldoCuenta" name="txtSaldoCuenta" maxlength="20" onkeypress="return soloDecimales(event)" value="'.round($cuenta->saldo,decimales).'" style="width:100px" />
			</td>
		</tr>
		
	</table>
</form>';
