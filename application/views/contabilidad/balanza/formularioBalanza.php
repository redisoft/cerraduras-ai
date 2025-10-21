<script>
$(document).ready(function()
{
	$("#txtFechaBalanza").monthpicker();
});
</script>
<?php
echo'
<div id="registrandoInformacion"></div>
<form id="frmBalanza" name="frmBalanza">
	<table class="tablaFormularios">
		<tr>
			<td class="etiquetas">RFC:</td>
			<td>
				'.$configuracion->rfc.'
				<input type="hidden" id="txtRfc" name="txtRfc" value="'.$configuracion->rfc.'" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Fecha:</td>
			<td>
				<input type="text" class="textosMes" id="txtFechaBalanza" name="txtFechaBalanza" readonly="readonly" value="'.date('Y-m').'" />
			</td>
		</tr>
	</table>
	
	<!--<label>El sistema usara el catálogo de cuentas del mes seleccionado</label>-->
	
</form>';
