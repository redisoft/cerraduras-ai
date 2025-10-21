<script>

$("#tablaArqueo tr:even").addClass("sombreado");
$("#tablaArqueo tr:odd").addClass("sinSombra");  

</script>
<?php

echo '
<table class="admintable" style="width:100%" id="tablaArqueo">';

	$fondoInicial		= 0;
	$efectivo			= 0;
	$retiros			= 0;
	
	$totalEfectivo		= $fondoInicial+$efectivo+$retiros;
	$sumaDenominaciones	= 0;
	
	echo '

	<tr>
		<td >Fondo de caja:</td>
		<td  align="right">$ '.number_format(0,decimales).'</td>
	</tr>
	<tr>
		<td >Efectivo:</td>
		<td  align="right">$ '.number_format(0,decimales).'</td>
	</tr>
	<tr>
		<td >Diferencia:</td>
		<td  align="right">$ '.number_format(0,decimales).'</td>
	</tr>
</table>';


