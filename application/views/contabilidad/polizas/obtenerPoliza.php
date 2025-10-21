<script>
$(document).ready(function()
{
	$("#txtFechaPoliza").monthpicker();
});
</script>
<?php
echo'
<div id="registrandoInformacion"></div>
<form id="frmPolizas" name="frmPolizas">
	<table class="tablaFormularios">
		<tr>
			<td class="etiquetas">Versión:</td>
			<td>'.$poliza->version.'</td>
		</tr>
		
		<tr>
			<td class="etiquetas">RFC:</td>
			<td>
				'.$poliza->rfc.'
				<input type="hidden" id="txtIdBalanza" name="txtIdPoliza" value="'.$poliza->idPoliza.'" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Fecha:</td>
			<td>
				<input type="text" class="textosMes" id="txtFechaPoliza" name="txtFechaPoliza" readonly="readonly" value="'.substr($poliza->fecha,0,7).'" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Tipo de solicitud:</td>
			<td>
				<select id="selectTipoSolicitud" name="selectTipoSolicitud" class="selectTextos" style="margin-top:2px;">
					<option '.($poliza->tipoSolicitud=='AF'?'selected="selected"':'').' value="AF">Acto de Fiscalización</option>
					<option '.($poliza->tipoSolicitud=='FC'?'selected="selected"':'').' value="FC">Fiscalización Compulsa</option>
					<option '.($poliza->tipoSolicitud=='DE'?'selected="selected"':'').' value="DE">Devolución</option>
					<option '.($poliza->tipoSolicitud=='CO'?'selected="selected"':'').' value="CO">Compensación</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Número de orden:</td>
			<td>
				<input type="text" class="textosFechas" id="txtNumeroOrden" name="txtNumeroOrden" maxlength="13" value="'.$poliza->numeroOrden.'" />
			</td>
		</tr>
		<tr>
			<td class="etiquetas">Número de tramite:</td>
			<td>
				<input type="text" class="textosFechas" id="txtNumeroTramite" name="txtNumeroTramite" maxlength="10" value="'.$poliza->numeroTramite.'"/>
			</td>
		</tr>
		
	</table>
</form>';
