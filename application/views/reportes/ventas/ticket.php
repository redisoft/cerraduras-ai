
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>&nbsp;</title>
		<script>
			window.print();
		</script>
    <style>
		html
		{
			font-family: Sans-Serif;
			font-size: 10px;
			
		}
		body
		{
			margin: 0;
			padding:0;
			width: 7.6cm;
			padding-left: 0.2cm;
			padding-right: 0.2cm;
			
		}
		
		table
		{
			margin: 0;
			padding:0;
		}
	</style>
	<body>
	
	<div align="center">
		<?=$configuracion->nombre?>
	</div>
		
	<div align="center">
		REIMPRESIÓN DE COMPROBANTES<br> DEL <?=obtenerFechaMesCorto($inicio)?> AL <?=obtenerFechaMesCorto($fin)?><br>
		========================================
	</div>
<?php
if($ventas!=null)
{
	?>
	
	<table class="admintable" width="100%" >
		<tr>
			<th align="center">FECHA</th>
			<th class="" align="center">FAC/REM</th>
			<th class="" align="center">SUBTOTAL</th>
			<th class="" align="center">I.V.A</th>
			<th class="" align="center">TOTAL</th>
			<th class="" align="center">CONDICIÓN</th>
		</tr>

    <?php
	    
	$i			= 1;
	$total		= 0;
	$iva		= 0;
	$subTotal	= 0;
	foreach($ventas as $row)
	{

		echo '
		<tr>
			<td align="center">'.obtenerFechaMesNumero($row->fechaCompra).'</td>
			<td align="center">'.$row->estacion.'-'.$row->folio.'</td>';
			
			if($row->cancelada=='0')
			{
				$subTotal	+= $row->subTotal;
				$iva		+= $row->iva;
				$total		+= $row->total;

				echo '
				<td align="right">$'.number_format($row->subTotal,2).'</td>
				<td align="right">$'.number_format($row->iva,2).'</td>
				<td align="right">$'.number_format($row->total,2).'</td>
				<td align="left">'.obtenerCondicionPago(strlen($row->formaPagoIngreso)>0?$row->formaPagoIngreso:$row->formaPagoVenta).'</td>';
			}
			else
			{
				echo '
				<td align="left" colspan="4">*** C A N C E L A D A ***</td>';
			}
		
		
		echo'
		</tr>';
		$i++;
	}
	
	echo '
	<tr>
		<td colspan="2"></td>
		<td align="right"><strong>$'.number_format($subTotal,2).'</strong></td>
		<td align="right"><strong>$'.number_format($iva,2).'</strong></td>
		<td align="right"><strong>$'.number_format($total,2).'</strong></td>
		<td></td>
	</tr>';
	
	?>
    </table>
    <?php
}

?>

	</body>
</html>