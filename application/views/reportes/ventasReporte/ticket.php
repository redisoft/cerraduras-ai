<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>&nbsp;</title>
   	<script>
		//window.print();
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
		<div align="center" style="font-size:20px">Fecha: '.obtenerFechaMesCorto($fecha).'<br></div>
		<table style="font-size:20px" width="100%">
			<tr>
				<th style="width:70%;">Departamento</th>
				<th style="width:30%;">Total</th>
			</tr>';
			
			$total=0;
			foreach($ventas as $row)
			{
				#$impuesto		= $row->impuestos/$row->cantidad;
				
				echo'
				<tr>
					<td align="center">'.$row->departamento.' '.$row->tipoVenta.'</td>
					<td align="right">$'.number_format($row->importe,2).'</td>
				</tr>';
				
				$total+=$row->importe;
			}
	
			echo'
			<tr>
				<th align="right">Total:</th>
				<th align="right">$'.number_format($total,2).'</th>
			</tr>
		</table>
	</div>';
	
	#echo $total;
?>

</body>
</html>
