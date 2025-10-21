<?php
echo'
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Cliente</td>
		<td>'.$cliente->empresa.'</td>
	</tr>	
	<tr>
		<td class="key">Serie:</td>
		<td>'.$cotizacion->serie.'</td>
	</tr>	
	
	<tr>
		<td class="key">Total:</td>
		<td>$'.number_format($cotizacion->total,2).'</td>
	</tr>
	
	<tr>
		<td class="key">Convertir a prefactura:</td>
		<td>
			&nbsp;
			<input type="checkbox" id="chkPrefactura" value="1"  />
		</td>
	</tr>
	
	<tr style="display: none">
		<td class="key">Orden:</td>
		<td>
			<input type="text" class="cajas" id="txtOrdenVenta" value="VEN-'.$folio.'" readonly="readonly" />
			<input type="hidden"  id="txtIdCotizacion" value="'.$idCotizacion.'" />
			<input type="hidden"  id="txtIdClienteCotizacion" value="'.$cliente->idCliente.'" />
		</td>
	</tr>
</table>';