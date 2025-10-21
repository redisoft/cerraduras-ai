<?php
$descuento		=($cotizacion->descuento/100)*$cotizacion->subTotal;
$subTotal		=$cotizacion->subTotal-$descuento;
$iva			=$subTotal*$cotizacion->iva;

echo'
<table class="admintable" width="100%;" >
	<tr>
		<th colspan="2">Detalles de venta</th>
	</tr>
	<tr>
	<tr>
		<td class="key">Orden de venta: </td>
		<td>'.$cotizacion->ordenCompra.'</td>
	</tr>
	<tr>
		<td class="key">Subtotal: </td>
		<td>$ '.number_format($cotizacion->subTotal,2).'</td>
	</tr>
	
	<tr>
		<td class="key">Descuento '.number_format($cotizacion->descuento,2).'%: </td>
		<td>$ '.number_format($descuento,2).'</td>
	</tr>
	<tr>
		<td class="key">IVA '.number_format($cotizacion->iva*100,2).'%: </td>
		<td>$ '.number_format($iva,2).'</td>
	</tr>
	<tr>
		<td class="key">Cobro total: </td>
		<td>$ '.number_format($cotizacion->total,2).'</td>
	</tr>
	<tr>
		<td class="key">Monto cobrado: </td>
		<td>$ '.number_format($total->pago,2).'</td>
	</tr>
	<tr>
		<td class="key">Deuda: </td>
		<td>$ '.number_format($cotizacion->total-$total->pago,2).'</td>
	</tr>
	
	<tr>
		<th colspan="2">Datos del cliente</th>
	</tr>
	<tr>
	<tr>
		<td class="key">Cliente: </td>
		<td>'.$cotizacion->empresa.'</td>
	</tr>
	<tr>
		<td class="key">Contacto: </td>
		<td>'.$cotizacion->contacto.'</td>
	</tr>
	
	<tr>
		<td class="key">Teléfono: </td>
		<td>'.$cotizacion->telefono.'</td>
	</tr>
	
	<tr>
		<td class="key">Email: </td>
		<td>'.$cotizacion->email.'</td>
	</tr>
	
</table>';

if(!empty ($pagos))
{
	echo'<table class="admintable" width="100%;" style="margin-top:3px" >
	<thead>
		<th style="width:130px;">Fecha Hora/Pago</th>
		<th>Forma de cobro</th>
		<th>No Transferencia</th>
		<th>No.Cheque</th>
		<th style="width:130px;">Cobro</th>';
	
	$i=1;
	
	foreach($pagos as $row)
	{
		$estilo=$i%2>0?"class='sinSombra'":'class="sombreado"';

		echo'
		<tr '.$estilo.'>
			<td align="center" valign="middle">'.$row->fecha.' </td>
			<td align="center" valign="middle">'.$row->formaPago.' </td>
			<td align="center" valign="middle">'.$row->transferencia.' </td>
			<td align="center" valign="middle">'.$row->cheque.' </td>
			<td align="center" valign="middle">$ '.number_format($row->pago,2).' </td>
		</tr>';
		
		$i++;
	}

	echo'</table>';
}