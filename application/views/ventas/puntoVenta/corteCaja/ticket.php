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
	<div class="letraGeneral" style="width:302px" >
	<table class="admintablee" width="100%" style="font-size: 15px">
		<tr>
			<th colspan="2">
				CORTE DE CAJA
			</th>
		</tr>
		<tr>
			<td align="left">FECHA:</td>
			<td align="right">'.obtenerFechaMesCorto(date('Y-m-d')).'</td>
		</tr>
		<tr>
			<td align="left">HORA:</td>
			<td align="right">'.(date('H:i:s')).'</td>
		</tr>
		<tr>
			<td class="key">Fondo de caja</td>
			<td>$'.number_format($fondoCaja,decimales).'</td>
		</tr>';
		
		
		foreach($formas as $row)
		{
			echo '
			<tr>
				<td class="key">'.$row->forma.'</td>
				<td>$'.number_format($row->pago,decimales).'</td>
			</tr>';
		}
		
		
		echo'
		<tr>
			<td class="key">Retiro de efectivo</td>
			<td>$'.number_format($retiros,decimales).'</td>
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
