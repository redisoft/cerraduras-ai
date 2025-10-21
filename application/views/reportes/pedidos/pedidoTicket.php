<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>&nbsp;</title>
   	<script>
		window.print();
	</script>
    <style>
		body
		{
			margin: 0;
			padding:0;
		}
	</style>
<?php


echo'
<div class="letraGeneral" style="width:316px">

	<div align="center" style=" font-size:16px; border:1px solid red;">
		'.$configuracion->nombre.'
			<br/>

		<div align="center" style="font-weight:normal; font-size:16px;">
			'.($tienda!=null?$tienda->calle:$configuracion->direccion).' '.($tienda!=null?$tienda->numero:$configuracion->numero).'
		</div>
	<div>

	<table style="font-size:16px" width="100%">
		<tr>
			<td> Ticket: </td>
			<td>'.$cotizacion->folio.'</td>
		</tr>
		
		<tr>
			<td> Fecha: </td>
			<td>'.obtenerFechaMesCorto($cotizacion->fechaEntrega).' '.obtenerFormatoHora($cotizacion->hora).'</td>
		</tr>
		
		<tr>
			<td> Cajero: </td>
			<td>'.$cotizacion->usuario.'</td>
		</tr>
		
		<tr>
			<td> Sucursal: </td>
			<td>Almacen de producción</td>
		</tr>
		
		<tr>
			<td> Estado: </td>
			<td>'.$cotizacion->estado.'</td>
		</tr>
		
		<tr>
			<td> Proceso de entrega: </td>
			<td>'.$cotizacion->estado.'</td>
		</tr>
		
		<tr>
			<td colspan="2">'.$cotizacion->tipo.'</td>
		</tr>
		
		<tr>
			<td> Cliente: </td>
			<td>'.$cotizacion->empresa.'</td>
		</tr>
		
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		
		<tr>
			<td align="center" valign="top"> '.$cantidadLetras.'</td>
			<td align="left">
				TOTAL: $'.number_format($cotizacion->total,decimales).'<br>
				ABONO: $'.number_format($cotizacion->pagado,decimales).'<br>
				DEBE: $'.number_format($cotizacion->total-$cotizacion->pagado,decimales).'<br>
			</td>
		</tr>
		
	</table>
</div>';

?>

</body>
</html>
