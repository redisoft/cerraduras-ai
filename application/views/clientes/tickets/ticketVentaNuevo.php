
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>&nbsp;</title>
	<?php
	if($jquery=='1')
	{
		#JsBarcode("#codigoTicket", "'.$venta->folio.(strlen($venta->estacion)>0?' - '.$venta->estacion:'').'");
		echo '
		<script src="'.base_url().'js/jquery/jquery.js"></script>
		<script src="'.base_url().'js/bibliotecas/JsBarcode.all.js"></script>
		
		<script>
			$(document).ready(function()
			{
				JsBarcode("#codigoTicket", "'.$venta->idCotizacion.'");
				window.print();
			});
		</script>';
	}
	else
	{
		echo '
		<script>
			JsBarcode("#codigoTicket", "'.$venta->idCotizacion.'");
		</script>';
	}
	?>
    <style>
		html
		{
			font-family: Sans-Serif;
		}
		body
		{
			margin: 0;
			padding:0;
			
		}
	</style>
	<body>
<?php


	echo'
	
	<div class="letraGeneral" style="width:316px">

		<div align="center" style="font-weight:normal; font-size:16px;">
			'.$configuracion->nombre.'
				<br/>
				<br/>

				<div align="left" style="font-weight:normal; font-size:16px;">
						'.($tienda!=null?$tienda->calle:$configuracion->direccion).' N°'.($tienda!=null?$tienda->numero:$configuracion->numero).'
						'.($tienda!=null?$tienda->colonia:$configuracion->colonia).', '.($tienda!=null?$tienda->municipio:$configuracion->municipio). ', '
						.($tienda!=null?$tienda->estado:$configuracion->estado).', '.($tienda!=null?$tienda->estado:$configuracion->pais).', C.P. '.($tienda!=null?$tienda->codigoPostal:$configuracion->codigoPostal).'
						<br>
						TEL: '.($configuracion->telefono).'
						
						<br/>
						
						
						
						RFC: '.$configuracion->rfc.'
					
						<br/>
				</div>
 				<br/>
				<div align="left" style="font-weight:normal; font-size:18px;" >
					
					Fecha: <strong>'.obtenerFechaMesCortoHoraFormato($venta->fechaCompra).'</strong><br />
					Vendedor: '.$venta->usuario.'
				</div>
				<br>
				<div align="left" style="font-weight:normal; font-size:16px;">';
				
					if($venta->idDireccion==0)
					{
						echo '
						<span style="font-size:18px;">Nombre: '.$cliente->empresa.'</span><br>
						Dirección: '.$cliente->calle.' '.$cliente->numero.' '.$cliente->numeroInterior.' '.$cliente->colonia.'<br>
						Ciudad: '.$cliente->municipio.'<br>
						Teléfono: '.$cliente->telefono.'<br>';
					}
					else
					{
						echo '
						<span style="font-size:18px;">Nombre: '.$direccion->razonSocial.'</span><br>
						Dirección: '.$direccion->calle.' '.$direccion->numero.' '.$direccion->colonia.'<br>
						Ciudad: '.$direccion->municipio.'<br>
						Teléfono: '.$direccion->telefono.'<br>';
					}
					
					echo'
					
					Condición: '.($venta->idForma=='7'?'Crédito':'Contado').'<br>
					
					<span style="font-size: 22px; font-weight: bold">'.($venta->prefactura==0?'Remisión':'Nota P/Factura').': '.$venta->folio.(strlen($venta->estacion)>0?' - '.$venta->estacion:'').'</span>

				</div>

		<div>

	<table style="font-size:18px" width="100%">
	<tr>
		<td style="border:none" colspan="4">
			==================================
		</td>
	</tr>
		<tr>
			<th style="font-weight:normal" align="left" colspan="4">Descripción</th>
		</tr>
		<tr>
			<th style="font-weight:normal" align="left" colspan="4">Clave</th>
		</tr>
		<tr>
			<th style="width:15%; font-weight:normal">Cant.</th>
			<th style="width:23%; font-weight:normal">PU</th>
			<!--<th style="width:23%;">Desc</th>-->
			<th style="width:23%; font-weight:normal" colspan="2">Importe</th>
		</tr>';

		foreach($productos as $row)
		{
			$impuesto		= $row->impuestos/$row->cantidad;
			
			echo'
			<tr>
				<td colspan="4">'.$row->nombre.($row->idPedimento>0?'<br />Pedimento: '.$row->pedimento.'<br />Fecha: '.$row->fecha:'').'</td>
			</tr>
			<tr>
				<td colspan="4">'.$row->codigoInterno.'</td>
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
			<td align="center"  colspan="4" >
				(** '.$cantidadLetras.' **)
			<br><br><br><br><br>
			</td>
		</tr>

		<tr>
			<td align="center"  colspan="4" style="font-size: 24px" >
				Total: $'.number_format($venta->total,2).'
			</td>
		</tr>
		

	</table>
	</div>



		<div id="codigoT" ><img id="codigoTicket" /></div>
	
	</div>
	
	
	<div align="center" style="font-size:16px"><br/>
		El importe de esta NOTA está incluida en la factura de contado del día. Revise su mercancía NO se admiten cambios ni devoluciones.
	</div>';

?>

</body>
</html>
