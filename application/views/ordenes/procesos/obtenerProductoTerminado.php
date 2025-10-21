<?php
echo'
<input type="hidden" name="txtIdDetalleProductoTerminado" id="txtIdDetalleProductoTerminado" class="cajas" style="width:160px;" value="'.$idDetalle.'"  />
<table class="admintable" width="100%">
	<tr>
		<td class="key">Producto:</td>
		<td>'.$orden->producto.'</td>
	</tr>
	<tr>
		<td class="key">Superviso:</td>
		<td><input type="text" name="txtSupervisoEditar" id="txtSupervisoEditar" class="cajas" style="width:160px;" value="'.$orden->superviso.'"  /> </td>
	</tr>
	<tr>
	  <td class="key">Fecha:</td>
	  <td>
		<input readonly="readonly" name="txtFechaProducidoEditar" id="txtFechaProducidoEditar" type="text" class="cajas" style="width:100px" value="'.substr($orden->fechaProduccion,0,10).'" />
		
	  </td>
	</tr>	
	
	<tr>
	  <td class="key">Fecha:</td>
	  <td>
		<input readonly="readonly" name="txtFechaCaducidadEditar" id="txtFechaCaducidadEditar" type="text" class="cajas" style="width:100px" value="'.substr($orden->fechaCaducidad,0,10).'" />
		
	  </td>
	</tr>	
	
	<tr>
	  <td class="key">Cantidad:</td>
	  <td>
		  <input type="text" class="cajasSelect" name="txtCantidadProducidoEditar" id="txtCantidadProducidoEditar" value="'.round($orden->cantidad).'" onkeypress="return soloDecimales(event)" />
	  </td>
	</tr>	
</table>
<script>
	$("#txtFechaProducidoEditar,#txtFechaCaducidadEditar").datepicker({ changeMonth: true });
</script>';
