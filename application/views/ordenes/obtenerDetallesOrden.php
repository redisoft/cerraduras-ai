<?php
echo'
<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Detalles de orden de producción</th>
	</tr>
	<tr>
		<td class="key">Fecha:</td>
		<td>'.obtenerFechaMesCortoHora($orden->fechaRegistro).'</td>
	</tr>
	<tr>
		<td class="key">Orden:</td>
		<td>'.$orden->orden.'</td>
	</tr>
	<tr>
		<td class="key">Producto:</td>
		<td>'.$orden->producto.'</td>
	</tr>
	<tr>
		<td class="key">Cantidad:</td>
		<td>'.round($orden->cantidad,4).'</td>
	</tr>
	<tr>
		<td class="key">Motivos de cancelación:</td>
		<td>
			<textarea class="TextArea" style="height:50px; width:200px" id="txtMotivosCancelacion" name="txtMotivosCancelacion"></textarea>
			<input type="hidden" id="txtIdOrden" value="'.$orden->idOrden.'" />
		</td>
	</tr>
</table>';
