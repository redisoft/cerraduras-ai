<?php
echo'
<tr id="filaComprobante'.$i.'">
	<td class="numeral	">'.$i.'</td>
	<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosUuid" id="txtUuid'.$i.'" name="txtUuid'.$i.'" placeholder="UUID" maxlength="50" /></td>
	<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCantidades" id="txtMonto'.$i.'" name="txtMonto'.$i.'" placeholder="$0.00" maxlength="15" onchange="comprobarCantidad(this)" onkeypress="return soloDecimales(event)" /></td>
	<td align="center"><input onclick="seleccionarTexto(this)" type="text" class="textosBalanzaCuentasChicas" id="txtRfc'.$i.'" name="txtRfc'.$i.'" placeholder="RFC" maxlength="13" /></td>
	<td class="vinculos">
		<img src="'.base_url().'img/borrar.png" title="Borrar comprobante" onclick="borrarComprobanteNuevo('.$i.')" />
		<input type="hidden" id="txtIdComprobante'.$i.'" name="txtIdComprobante'.$i.'" value="0" />
	</td>
<tr>';
?>