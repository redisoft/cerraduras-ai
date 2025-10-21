<script>
$(document).ready(function()
{
	$("#txtFechaPoliza").datepicker();
});
</script>
<?php
echo'
<div id="registrandoInformacion"></div>
<form id="frmPolizas" name="frmPolizas">
	<table class="admintable" style="width:100%">
		<tr>
			<td class="key">Fecha:</td>
			<td>
				<input type="text" class="cajas" id="txtFechaPoliza" name="txtFechaPoliza" readonly="readonly" value="'.date('Y-m-d').'" style="width:100px" />
			</td>
		</tr>

		<tr>
			<td class="key">Tipo de póliza:</td>
			<td>
				<select class="cajas" id="selectPolizas" name="selectPolizas" style="width:200px">
					<option value="1">Ingresos</option>
					<option value="2">Egresos</option>
					<option value="3">Diario</option>
				</select>
			</td>
		</tr>
		
	</table>
</form>';
