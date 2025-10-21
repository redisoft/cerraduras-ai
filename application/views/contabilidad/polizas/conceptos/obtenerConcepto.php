<script>
$(document).ready(function()
{
	$("#txtFechaConcepto").datepicker();
});
</script>
<?php
echo'
<div id="registrandoInformacion"></div>
<form id="frmConceptos" name="frmConceptos">
	<table class="tablaFormularios">
		<tr>
			<td class="etiquetas">Tipo de póliza:</td>
			<td>
				<select id="selectTipo" name="selectTipo" class="selectTextos">
					<option value="1">Ingreso</option>
					<option '.($concepto->tipo=='2'?'selected="selected"':'').' value="2">Egreso</option>
					<option '.($concepto->tipo=='3'?'selected="selected"':'').' value="3">Diario</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Número:</td>
			<td>
				'.obtenerPolizaNombre($concepto->tipo,$polizas).'<input type="text" class="textosFechas" id="txtNumero" name="txtNumero" maxlength="50" value="'.$concepto->numero.'" />
				<input type="hidden" id="txtIdConcepto" name="txtIdConcepto" value="'.$concepto->idConcepto.'" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Fecha:</td>
			<td>
				<input type="text" class="textosFechas" id="txtFechaConcepto" name="txtFechaConcepto" readonly="readonly" value="'.$concepto->fecha.'" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Concepto:</td>
			<td>
				<input type="text" class="textos" id="txtConcepto" name="txtConcepto" maxlength="300" value="'.$concepto->concepto.'" />
			</td>
		</tr>
		
	</table>
</form>';
