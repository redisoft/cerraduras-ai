<script>
$(document).ready(function()
{
	$("#txtCriterio").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerCuentasCatalogo();
		}, milisegundos);
	});
});

</script>
<?php
echo '
<div id="procesandoCuentas"></div>

<table class="admintable" width="100%">
	<tr>
		<th colspan="2">Detalles de catálogo</th>
	</tr>
	<tr>
		<td class="key">RFC:</td>
		<td>
			'.$catalogo->rfc.'
			<input type="hidden" id="txtIdCatalogo" name="txtIdCatalogo" value="'.$catalogo->idCatalogo.'" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Fecha:</td>
		<td>'.obtenerMesAnio($catalogo->fecha).'</td>
	</tr>
	
	<tr>
		<td class="key">Número de cuentas:</td>
		<td>'.$catalogo->numeroCuentas.'</td>
	</tr>
	
	<tr>
		<td class="key">Buscar:</td>
		<td>
			<input type="text" class="cajas" id="txtCriterio" name="txtCriterio" placeholder="Por descripción, número de cuenta  y subcuenta" style="width:600px" />
		</td>
	</tr>
	
</table>

<div id="obtenerCuentasCatalogo"></div>';
?>