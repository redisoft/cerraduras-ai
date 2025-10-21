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
	<table class="admintable">
		<tr>
			<td class="key">RFC:</td>
			<td>
				'.$configuracion->rfc.'
				<input type="hidden" id="txtRfc" name="txtRfc" value="'.$configuracion->rfc.'" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" class="cajasMes" id="txtFechaCatalogo" name="txtFechaCatalogo" readonly="readonly" value="'.date('Y-m').'" />
			</td>
		</tr>
		<tr>
			<td class="key">Copiar:</td>
			<td>
				<input type="checkbox" id="chkCopiar" name="chkCopiar" checked="checked" value="1" />
				<label>Copiar el catálogo del último mes</label>
			</td>
		</tr>
		
	</table>
</form>';
