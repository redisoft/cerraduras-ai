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
			font-size:12px;
		}
	</style>
<?php
echo '
<link rel="stylesheet" type="text/css" href="'.base_url().'css/adm/ticket.css" />
</head>
<body>';

	echo'
	<div class="letraGeneral" style="width:215px" >
	<!--<div style="margin-left:20px">
		<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="height:100px; width:140px;" />
	</div>-->
	<div align="center" style="font-weight:bold; font-size:20px;">
	'.$configuracion->nombre.'
	<br/>
	<!--Sucursal: '.($tienda!=null?$tienda->nombre:'').'-->
		
		<div align="left" style="font-weight:normal; font-size:18px;">
		Dirección: '.($tienda!=null?$tienda->calle:$configuracion->direccion).' N°'.($tienda!=null?$tienda->numero:$configuracion->numero).'
		'.($tienda!=null?$tienda->colonia:$configuracion->colonia).', '.($tienda!=null?$tienda->municipio:$configuracion->municipio). ', '
		.($tienda!=null?$tienda->estado:$configuracion->estado).', '.($tienda!=null?$tienda->estado:$configuracion->pais).', C.P. '.($tienda!=null?$tienda->codigoPostal:$configuracion->codigoPostal).'
		<br/>
		RFC:'.$configuracion->rfc.'
	<!--Régimen Fiscal: '.$configuracion->regimenFiscal.'-->
		<br/>
		</div>';

	echo'<br>
	    <div align="left" style="font-weight:bold; font-size:18px;" >
	Nota de venta: '.$venta->folio.'<br />
	Fecha: '.obtenerFechaMesCortoHora(date('Y-m-d h:i:s')).'<br />
	 </div>
        </div>
	<div>
	===========================================<br />
	<table class="admintablee" width="100%">
		<tr>
			<th align="left" colspan="4">Descripcion</th>
		</tr>
		<tr>
			<th style="width:15%;">Cant.</th>
			<th style="width:23%;">PU</th>
			<th style="width:23%;">Desc</th>
			<th style="width:23%;">Importe</th>
		</tr>';
		
		foreach($productos as $row)
		{
			echo'
			<tr>
				<td colspan="4">'.$row->nombre.'</td>
			</tr>
			<tr>
				<td align="center">'.number_format($row->cantidad,2).'</td>
				<td align="center">$'.number_format($row->precio,2).'</td>
				<td align="center">$'.number_format($row->descuento,2).'</td>
				<td align="right">$'.number_format($row->importe,2).'</td>
			</tr>';
		}
		
		echo'
		<tr>
			<td style="border:none" colspan="4">
				===========================================
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
			<td colspan="3" align="right">Impuestos:($'.text_format($iva->iva).') </td>
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
	
	<div align="center" style="font-size:8px"><br />
	
	ESTE TICKET FORMA PARTE DE LA FACTURACIÓN DIARIA	
	</div>
	</div>';

?>

</body>
</html>
