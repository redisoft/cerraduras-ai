<?php
echo '
<script>
	$("#txtFechaInicialPeriodo,#txtFechaFinalPeriodo").datepicker();
</script>
<form id="frmEditarPeriodo">
<table class="admintable" width="100%;">
	<tr>
		<td class="key">Nombre:</td>
		<td>
			<input name="txtPeriodo" id="txtPeriodo" type="text" class="cajas" style="width:300px" value="'.$periodo->nombre.'"  />
			<input value="'.$periodo->idPeriodo.'" id="txtIdPeriodo" name="txtIdPeriodo" type="hidden" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha inicial:</td>
		<td>
			<input type="text" class="cajas" name="txtFechaInicialPeriodo" id="txtFechaInicialPeriodo" style="width:80px" value="'.$periodo->fechaInicial.'"/>
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha final:</td>
		<td>
			<input type="text" class="cajas" name="txtFechaFinalPeriodo" id="txtFechaFinalPeriodo" style="width:80px" value="'.$periodo->fechaFinal.'"/>
		</td>
	</tr>
</table>

</form>';