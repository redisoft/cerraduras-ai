<?php
$i=$limite;

if($cuentas!=null)
{
	echo '
	
	<div id="procesandoInformacion"></div>
	
	<div align="center">
		<ul id="pagination-digg" class="ajax-pagNiveles">'.$this->pagination->create_links().'</ul>
	</div>';

	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaNivel1 tr:even").addClass("abajo");
		$("#tablaNivel1 tr:odd").addClass("arriba");  
	});
	</script>
	
	<table class="tablaDatos" id="tablaNivel1">
		<tr>
			<th class="titulos" colspan="5">Detalles de cuentas</th>
		</tr>
		<tr>
			<th>No.</th>
			<th>Código agrupador</th>
			<th>Cuenta</th>
			<th>Tipo</th>
			<th>Subcuentas</th>
		</tr>';
	
	foreach($cuentas as $row)
	{
		echo'
		<tr>
			<td class="numeral	">'.$i.'</td>
			<td align="center">'.$row->codigo.'</td>
			<td align="left">'.$row->nombre.'</td>
			<td align="center">'.$row->cuenta.'</td>
			
			<td align="center" class="vinculos">
				<img src="'.base_url().'img/cuentas.png" title="Subcuentas nivel 2" onclick="obtenerNivel2('.$row->idCuenta.')" />
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>

	<div align="center">
		<ul id="pagination-digg" class="ajax-pagNiveles">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="erroresDatos">Sin registros</div>';
}
?>