<?php
echo '
<script>
	$("#txtFechaInicialPeriodo,#txtFechaFinalPeriodo").datepicker();
</script>
<form id="frmPeriodo">
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtPeriodo" id="txtPeriodo" type="text" class="cajas" style="width:300px"  />
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha inicial:</td>
		<td>
			<input type="text" class="cajas" name="txtFechaInicialPeriodo" id="txtFechaInicialPeriodo" style="width:80px" value="'.date('Y-m-d').'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha final:</td>
		<td>
			<input type="text" class="cajas" name="txtFechaFinalPeriodo" id="txtFechaFinalPeriodo" style="width:80px" value="'.date('Y-m-d').'"/>
		</td>
	</tr>
</table>
</form>';