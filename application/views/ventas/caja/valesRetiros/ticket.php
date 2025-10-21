<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <title>&nbsp;</title>
	<head>
		<style>
			html
			{
				font-family: Sans-Serif;
			}
			body
			{
				margin: 0;
				padding:0;

			}
		</style>
		<script>
			window.print();
		</script>
	</head>
	<body>
	<br><br>
<div class="letraGeneral" style="width:316px">
	<table class="admintable" width="100%">
		<tr>
			<td width="30%" class="key">Fecha:</td>
			<td width="30%"><?=obtenerFechaMesLargo($registro->fecha,0)?></td>
			<td width="10%" class="key">Hora:</td>
			<td width="30%"><?=date('H:i:s')?></td>
		</tr>
		
		<tr>
			<td class="key">Tipo:</td>
			<td colspan="3"><?=$registro->tipoRegistro=='1'?'Retiro':'Vale'?></td>
		</tr>
		<tr>
			<td class="key">Importe:</td>
			<td colspan="3">$<?=number_format($registro->pago,2)?></td>
		</tr>
		<tr>
			<td class="key">Descripción:</td>
			<td colspan="3"><?=$registro->producto?></td>
		</tr>
		

		
	</table>
	
	<br><br>

	<div align="center">
	________________________________<br>
		<?=$registro->idUsuario>0?$registro->usuario:$usuario->nombre?>
	</div>
</div>


<?php
if($registro->tipoRegistro=='1' and $registro->idUsuarioRetiro>0)
{
	?>

	<br><br>

	<div class="letraGeneral" style="width:316px">
		<table class="admintable" width="100%">
			<tr>
				<td width="30%" class="key">Fecha:</td>
				<td width="30%"><?=obtenerFechaMesLargo($registro->fecha,0)?></td>
				<td width="10%" class="key">Hora:</td>
				<td width="30%"><?=date('H:i:s')?></td>
			</tr>
		
			<tr>
				<td class="key">Tipo:</td>
				<td colspan="3"><?=$registro->tipoRegistro=='1'?'Retiro':'Vale'?></td>
			</tr>
			<tr>
				<td class="key">Importe:</td>
				<td colspan="3">$<?=number_format($registro->pago,2)?></td>
			</tr>
			<tr>
				<td class="key">Descripción:</td>
				<td colspan="3"><?=$registro->producto?></td>
			</tr>
		

		
		</table>
	
		<br><br>

		<div align="center">
		________________________________<br>
			<?=$registro->usuarioRetiro?>
		</div>
	</div>

	<?php
}
?>



		
</body>
</html>
