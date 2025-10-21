
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
			font-size: 12px;
			
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
		RELACIÓN DE ENVÍOS<br> DEL <?=obtenerFechaMesCorto($inicio)?> AL <?=obtenerFechaMesCorto($fin)?>
		<br>
		CHOFER:<?=$personal!=null?$personal->nombre:''?><br>
		VEHÍCULO: <?=$vehiculo!=null?$vehiculo->modelo.', '.$vehiculo->marca:''?>
		
		<br>
		========================================
	</div>
<?php
if($ventas!=null)
{
	?>
	
	<table class="admintable" width="100%" >
		<tr>
			<th align="center">FECHA</th>
			<th class="" align="center">HORA</th>
			<th class="" align="center">LOCALIDAD</th>
		</tr>

		<tr>
			<th colspan="3" align="center">CLIENTE</th>
		</tr>
		
		<tr>
			<td colspan="3" align="left">
				========================================
			</td>
		</tr>

    <?php
	    
	$i=1;
	$total=0;
	foreach($ventas as $row)
	{
		
		?>
		<tr>
			<td align="center"><?php echo obtenerFechaMesCorto($row->fechaCompra)?></td>
			<td align="center"><?php echo obtenerHora($row->fechaCompra)?></td>
			<td align="left"><?php echo $row->ruta?></td>
		</tr>
		
		<tr>
			<td colspan="3" align="left"><?php echo $row->empresa?></td>
		</tr>
		
		<tr>
			<td align="center">P/Fac-<?php echo $row->estacion.' '.$row->folio?></td>
			<td align="center">Importe: $<?php echo number_format($row->total,2)?></td>
			<td align="left">Debe: $<?php echo number_format($row->saldo,2)?></td>
		</tr>
		
		<tr>
			<td colspan="3" align="left">&nbsp;</td>
		</tr>

		<?php
		$i++;
	}
	
	?>
    </table>
	<br />
	<div align="center">
		_________________________________ <br />
		Nombre y firma
	</div>
    <?php
}
else
{
	echo '<div class="Error_validar">Sin registros</div>';
}
?>

	</body>
</html>
