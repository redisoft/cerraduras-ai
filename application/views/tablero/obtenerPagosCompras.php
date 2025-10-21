<?php
$contacto	= explode('|',$compra->contacto);
echo
'<table class="admintable" width="100%" >
	<tr>
		<th colspan="2">Detalles de la compra</th>
	</tr>

	<tr>
		<td class="key">Compra: </td>
		<td>'.$compra->nombre.'</td>
	</tr>
	
	<tr>
		<td class="key">SubTotal: </td>
		<td>$ '.number_format($compra->subTotal,2).'</td>
	</tr>
	
	<tr>
		<td class="key">Descuento: </td>
		<td>$ '.number_format($compra->descuento,2).'</td>
	</tr>
	
	<tr>
		<td class="key">IVA: </td>
		<td>$ '.number_format($compra->iva,2).'</td>
	</tr>
	
	
	<tr>
		<td class="key">Pago total: </td>
		<td>$ '.number_format($compra->total,2).'</td>
	</tr>
	
	<tr>
		<td class="key">Monto pagado: </td>
		<td>$ '.number_format($total->pago,2).'</td>
	</tr>
	
	<tr>
		<td class="key">Deuda: </td>
		<td>$ '.number_format($compra->total-$total->pago,2).'</td>
	</tr>
	
	<tr>
		<th colspan="2">Datos del proveedor</th>
	</tr>
	
	<tr>
		<td class="key">Proveedor: </td>
		<td>'.$compra->empresa.'</td>
	</tr>
	
	<tr>
		<td class="key">Contacto: </td>
		<td>'.(isset($contacto[0])?$contacto[0]:'').'</td>
	</tr>
	
	<tr>
		<td class="key">Teléfono: </td>
		<td>'.(isset($contacto[1])?$contacto[1]:'').'</td>
	</tr>
	
	<tr>
		<td class="key">Email: </td>
		<td>'.(isset($contacto[2])?$contacto[2]:'').'</td>
	</tr>
	
</table>';

if(!empty ($pagos))
{
	echo'
	<table class="admintable" width="100%" style="margin-top:3px" >
		<thead>
		<th style="width:130px;">Fecha Hora/Pago</th>
		<th>Forma de pago</th>
		<th>No Transferencia</th>
		<th>No.Cheque</th>
		<th style="width:130px;">Pago</th>';
	
$i=1;

foreach($pagos as $row)
{
	$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
	
	echo
	'<tr '.$estilo.'>
		<td align="center" valign="middle">'.obtenerFechaMesCortoHora($row->fecha).' </td>
		<td align="center" valign="middle">'.$row->formaPago.' </td>
		<td align="center" valign="middle">'.$row->transferencia.' </td>
		<td align="center" valign="middle">'.$row->cheque.' </td>
		<td align="center" valign="middle">$ '.number_format($row->pago,2).' </td>
	</tr>';
	
	$i++;
}

echo'</table>';
}