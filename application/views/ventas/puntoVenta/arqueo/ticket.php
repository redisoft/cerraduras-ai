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

$totalDenominaciones=0;
echo '
<link rel="stylesheet" type="text/css" href="'.base_url().'css/adm/ticket.css" />
</head>
<body>';

	echo'
	<div class="letraGeneral" style="width:302px" >
	<table class="admintablee" width="100%">
		<tr>
			<th colspan="2">
				ARQUEO
			</th>
		</tr>
		<tr>
			<td align="left">FECHA:</td>
			<td align="right">'.obtenerFechaMesCorto(date('Y-m-d')).'</td>
		</tr>
	</table>
	
	<table class="admintablee" width="100%">

		<tr>
			<th colspan="3">Denominaciones</th>
		</tr>';
		
		if($denominaciones!=null)
		{
			foreach($denominaciones as $row)	
			{
				$totalDenominaciones	+= $row->valor*$row->cantidad;
				
				echo '
				<tr>
					<td align="center" >$'.$row->valor.'</td>
					<td  width="20%">
						'.$row->cantidad.'
					</td>
					
					<td  align="right" width="25%" >$ '.number_format($row->valor*$row->cantidad,decimales).'</td>
					
				</tr>';
			}
		}
		
		echo '
			<tr>
				<td align="right" class="totales" colspan="2">Total</td>
				<td align="right" class="totales" >'.number_format($totalDenominaciones,decimales).'</td>
			</tr>
		</table>';
		
		
		$total=$totalDenominaciones-$efectivo;
		
		echo '
		<table class="admintablee" style="width:100%" id="tablaArqueo">
			<tr>
				<th colspan="2">Arqueo</th>
			</tr>
			<tr>
				<td >Fondo de caja:</td>
				<td  align="right">$ '.number_format($fondoCaja,decimales).'</td>
			</tr>
			<tr>
				<td >Efectivo:</td>
				<td  align="right">$ '.number_format($efectivo,decimales).'</td>
			</tr>
			<tr>
				<td >Diferencia:</td>
				<td  align="right" '.($total>0?'style="color: red"':'').'>$ '.number_format($total,decimales).'</td>
			</tr>

	
			<tr>
				<td colspan="2" align="center">
					___________________________________
					<br>
					Firma
				</td>
			</tr>
		
	</table>

	</div>';

?>

</body>
</html>
