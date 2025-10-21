<?php
echo '
<table class="admintable" width="100%">
	
	<tr>
		<td class="key">Orden:</td>
		<td>'.$compra->nombre.'</td>
	</tr>
	
	<tr>
		<td class="key">Proveedor:</td>
		<td>'.$compra->empresa.'</td>
	</tr>

	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input value="'.date('Y-m-d H:i').'" readonly="readonly" id="txtFechaRecibido" type="text" class="cajas" style="width:120px" />
			<script>
				$("#txtFechaRecibido").datetimepicker({ changeMonth: true });
			</script>
			
			<input id="txtIdComprita"   	type="hidden" value="'.$compra->idCompras.'" />
			
		</td>
	</tr>

	<tr>
		<td class="key">Factura/Remisión:</td>
		<td>
			<select id="selectFactura" name="selectFactura" class="cajas" style="width:200px">
				<option value="1">Factura</option>
				<option value="0">Remisión</option>
			</select>
			<br />
			<input id="txtRemision" type="text" class="cajas" style="width:200px" />
		</td>
	</tr>
</table>';


