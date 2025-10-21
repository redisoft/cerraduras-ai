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
				'.$balanza->rfc.'
				<input type="hidden" id="txtIdBalanza" name="txtIdBalanza" value="'.$balanza->idBalanza.'" />
			</td>
		</tr>
		
		<tr>
			<td class="etiquetas">Fecha:</td>
			<td>
				<input type="text" class="textosMes" id="txtFechaBalanza" name="txtFechaBalanza" readonly="readonly" value="'.substr($balanza->fecha,0,7).'" />
			</td>
		</tr>
	</table>
</form>';
