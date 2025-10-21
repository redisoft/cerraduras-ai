<script>
$(document).ready(function()
{
	$("#txtFechaCatalogo").monthpicker();
});
</script>
<?php
echo'
<div id="registrandoInformacion"></div>
<form id="frmCatalogo" name="frmCatalogo">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">RFC:</td>
			<td>
				'.$catalogo->rfc.'
				<input type="hidden" id="txtIdCatalogo" name="txtIdCatalogo" value="'.$catalogo->idCatalogo.'" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" class="cajasMes" id="txtFechaCatalogo" name="txtFechaCatalogo" readonly="readonly" value="'.substr($catalogo->fecha,0,7).'" />
			</td>
		</tr>
	</table>
</form>';
