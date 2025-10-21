<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>&nbsp;</title>
   	<script>
		//window.print();
		$("#codigoTicket").barcode("<?php echo $venta->folio?>", "code93",{barWidth:2, barHeight:40})
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

		<div align="center" style="font-weight:bold; font-size:16px;">
			'.$configuracion->nombre.'
				<br/>
				<br/>

				<div align="left" style="font-weight:normal; font-size:16px;">
						Dirección: '.($tienda!=null?$tienda->calle:$configuracion->direccion).' N°'.($tienda!=null?$tienda->numero:$configuracion->numero).'
						'.($tienda!=null?$tienda->colonia:$configuracion->colonia).', '.($tienda!=null?$tienda->municipio:$configuracion->municipio). ', '
						.($tienda!=null?$tienda->estado:$configuracion->estado).', '.($tienda!=null?$tienda->estado:$configuracion->pais).', C.P. '.($tienda!=null?$tienda->codigoPostal:$configuracion->codigoPostal).'
						<br>
						TEL: '.($configuracion->telefono).'
						
						<br/>
						RFC: '.$configuracion->rfc.'
					
						<br/>
				</div>
 				<br/>
				<div align="left" style="font-weight:bold; font-size:16px;" >
					Nota de venta: '.$venta->folio.'<br />
					Fecha: '.obtenerFechaMesCortoHora(date('Y-m-d h:i:s')).'<br />
					Vendedor: '.$venta->usuario.'
				</div>
				<br>
				<div align="left" style="font-weight:normal; font-size:16px;">
				
					Nombre: '.$cliente->empresa.'<br>
					Dirección: '.$cliente->direccionEnvio.'<br>
					Ciudad: '.$cliente->municipioEnvio.'<br>
					Telefono: '.$cliente->telefono.'<br>

				</div>

		<div>

	<table style="font-size:16px" width="100%">
	<tr>
		<td style="border:none" colspan="4">
			==================================
		</td>
	</tr>
		<tr>
			<th align="left" colspan="4">Descripción</th>
		</tr>
		<tr>
			<th style="width:15%;">Cant.</th>
			<th style="width:23%;">PU</th>
			<!--<th style="width:23%;">Desc</th>-->
			<th style="width:23%;" colspan="2">Importe</th>
		</tr>';

		foreach($productos as $row)
		{
			$impuesto		= $row->impuestos/$row->cantidad;
			
			echo'
			<tr>
				<td colspan="4">'.$row->nombre.'</td>
			</tr>
			<tr>
				<td align="center">'.number_format($row->cantidad,2).'</td>
				<td align="center">$'.number_format($row->precio+$impuesto,2).'</td>
				<!--<td align="center">$'.number_format($row->descuento,2).'</td>-->
				<td align="right" colspan="2">$'.number_format($row->importe+$row->impuestos,2).'</td>
			</tr>';
		}

		echo'
		<tr>
			<td style="border:none" colspan="4">
			==================================
			</td>
		</tr>
		<tr>
			<td align="right" colspan="3">Subtotal: </td>
			<td align="right">$'.number_format($venta->subTotal,2).'</td>
		</tr>

		<!--<tr>
			<td colspan="3" align="right">Descuento: </td>
			<td align="right">
				$'.number_format($venta->descuento,2).'
			</td>
		</tr>-->

		<tr>
			<td colspan="3" align="right">Impuestos: </td>
			<td align="right" >
				$'.number_format($venta->iva,2).'
			</td>
		</tr>

		<tr>
			<td colspan="3" align="right">Total: </td>
			<td align="right" >
				$'.number_format($venta->total,2).'
			</td>
		</tr>
		<tr>
			<td colspan="3" align="right">Efectivo: </td>
			<td align="right">
				$'.number_format($venta->pago,2).'
			</td>
		</tr>
		<tr>
			<td colspan="3" align="right">Cambio: </td>
			<td align="right">
			 	$'.number_format($venta->cambio,2).'
			</td>
		</tr>

	</table>
	</div>

	<div align="center" style="font-size:14px"><br/>
		ESTE TICKET FORMA PARTE
		<br/>
		DE LA FACTURACIÓN DIARIA
	</div>
	
	
	<div id="codigoTicket"></div>
	
	</div>';

?>

</body>
</html>
