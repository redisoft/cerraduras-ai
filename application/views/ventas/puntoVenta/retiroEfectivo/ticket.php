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
	<table class="admintablee" width="100%">
		<tr>
			<th colspan="2">
				RETIRO DE EFECTIVO
			</th>
		</tr>
		<tr>
			<td align="left">FECHA:</td>
			<td align="right">'.obtenerFechaMesCorto($egreso->fecha).'</td>
		</tr>
		
		<tr>
			<td align="left">HORA:</td>
			<td align="right">'.obtenerHora($egreso->fecha).'</td>
		</tr>
		
		<tr>
			<td align="left">Importe:</td>
			<td align="right" >$'.number_format($egreso->pago,decimales).'</td>
		</tr>
		
		<tr>
			<td align="left">Motivos:</td>
			<td align="right">'.($egreso->comentarios).'</td>
		</tr>
		
		<tr>
			<td colspan="2" align="center">
				&nbsp;
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				&nbsp;
			</td>
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
