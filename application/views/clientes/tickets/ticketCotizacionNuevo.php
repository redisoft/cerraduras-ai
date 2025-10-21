
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>&nbsp;</title>
	<?php
	if($jquery=='1')
	{
		echo '
		<script src="'.base_url().'js/jquery/jquery.js"></script>
		<script src="'.base_url().'js/bibliotecas/JsBarcode.all.js"></script>
		
		<script>
			$(document).ready(function()
			{
				JsBarcode("#codigoTicket", "'.$venta->folioCotizacion.(strlen($venta->estacion)>0?' - '.$venta->estacion:'').'");
				window.print();
			});
		</script>';
	}
	else
	{
		echo '
		<script>
			JsBarcode("#codigoTicket", "'.$venta->folioCotizacion.(strlen($venta->estacion)>0?' - '.$venta->estacion:'').'");
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
					
					Fecha: <strong>'.obtenerFechaMesCortoHoraFormato($venta->fecha).'</strong><br />
					Vendedor: '.$venta->usuario.'
				</div>
				<br>
				<div align="left" style="font-weight:normal; font-size:16px;">
				
					<span style="font-size:18px;">Nombre: '.$cliente->empresa.'</span><br>
					Dirección: '.$cliente->calle.' '.$cliente->numero.' '.$cliente->numeroInterior.' '.$cliente->colonia.'<br>
					Ciudad: '.$cliente->municipio.'<br>
					Teléfono: '.$cliente->telefono.'<br>
					
					<span style="font-size: 22px">Cotización: '.$venta->folioCotizacion.(strlen($venta->estacion)>0?' - '.$venta->estacion:'').'</span>

				</div>

		<div>

	<table style="font-size:18px" width="100%">
		<tr>
			<td style="border:none" colspan="2">
				==================================
			</td>
		</tr>
		<tr>
			<th style="font-weight:normal" align="left" colspan="2" >Descripción</th>
		</tr>
		<tr>
			<th style="font-weight:normal" width="60%" align="left">Clave</th>
			<th style="font-weight:normal" align="left">Cant.</th>
		</tr>';

		foreach($productos as $row)
		{
			$impuesto		= $row->impuestos/$row->cantidad;
			
			echo'
			<tr>
				<td colspan="2">'.$row->nombre.'</td>
			</tr>
			<tr>
				<td >'.$row->codigoInterno.'</td>
				<td align="left">'.number_format($row->cantidad,2).'</td>
			</tr>';
		}

		echo'
		<tr>
			<td style="border:none"colspan="2" >
			==================================
			</td>
		</tr>
		
		
		<tr>
			<td align="center"  colspan="2" >
				(** '.$cantidadLetras.' **)
			<br><br>
			</td>
		</tr>

		<tr>
			<td align="center"  style="font-size: 24px" colspan="2">
				Total: $'.number_format($venta->total,2).'
			</td>
		</tr>
		

	</table>
	</div>


	
	<div id="codigoT" ><img id="codigoTicket" /></div>
	
	</div>
	
	
	<div align="center" style="font-size:16px"><br/>
		** COTIZACIÓN ** 
		<br>
		Precios sujetos a cambio sin previo aviso 
		
		<br>
		** COTIZACIÓN **
	</div>';

?>

</body>
</html>
