<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>&nbsp;</title>
   	<script>
		window.print();
	</script>
<?php
echo '
<link rel="stylesheet" type="text/css" href="'.base_url().'css/adm/ticket.css" />
</head>
<body>';

	echo'
	<div class="letraGeneral" style="width:250px" >
	<div style="margin-left:12px">
		<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="height:100px; width:140px;" />
	</div>
	<div style="margin-left:12px">
	'.$configuracion->nombre.'
	<br />
	RFC:'.$configuracion->rfc.'
	<br />
	'.($tienda!=null?$tienda->calle:$configuracion->direccion).' N°'.($tienda!=null?$tienda->numero:$configuracion->numero).'
	<br />
	'.($tienda!=null?$tienda->colonia:$configuracion->colonia).', '.($tienda!=null?$tienda->municipio:$configuracion->municipio).' <br />'. 
	($tienda!=null?$tienda->estado:$configuracion->estado).',C.P. '.($tienda!=null?$tienda->codigoPostal:$configuracion->codigoPostal).'
	<br />
	Régimen Fiscal: '.$configuracion->regimenFiscal.'
	<br />
	<br />';

	echo'<br>
	N. Ticket: '.$venta->folio.'<br />
	Fecha: '.date('Y-m-d h:i:s').'<br />
	</div>
	<div>
	================================<br />
	<table class="admintablee" width="100%">
		<tr>
			<th style="width:15%;">Cant.</th>
			<th style="width:57%;" >Descripcion</th>
			<th style="width:23%;">Costo</th>
		</tr>';
		
		foreach($productos as $row)
		{
			echo'
			<tr>
				<td>'.$row->cantidad.'</td>
				<td>'.$row->nombre.'</td>
				<td align="right">$'.number_format($row->importe,2).'</td>
			</tr>';
		}
		
		echo'
		<tr>
		<td style="border:none" colspan="3">
		===============================
		</td>
		</tr>
		<tr>
			<td align="right" colspan="3">
			Total: $ '.number_format($venta->total,2).'
			</td>
		</tr>
		<tr>
			<td align="right" colspan="3">
			Efectivo: $ '.number_format($venta->pago,2).'
			</td>
		</tr>
		<tr>
			<td align="right" colspan="3">
			Cambio: $ '.number_format($venta->cambio,2).'
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
