<?php
$i=$limite;

if($conceptos!=null)
{
	echo '
	<div id="procesandoConceptos"></div>
	
	<div align="center">
		<ul id="pagination-digg" class="ajax-pagConceptosPolizas">'.$this->pagination->create_links().'</ul>
	</div>';

	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaPolizas tr:even").addClass("abajo");
		$("#tablaPolizas tr:odd").addClass("arriba");  
	});
	</script>
	
	<table class="tablaDatos" id="tablaPolizas">
		<tr>
			<th class="titulos" colspan="7">Lista de conceptos de pólizas</th>
		</tr>
			
		<tr>
			<th>No.</th>
			<th>Tipo</th>
			<th>Pagado / Cobrado</th>
			<th>Número</th>
			<th>Fecha</th>
			<th>Concepto</th>
			<th width="15%">Operaciones</th>
		</tr>';
	
	foreach($conceptos as $row)
	{
		echo'
		<tr id="filaConcepto'.$row->idConcepto.'">
			<td class="numeral	">'.$i.'</td>
			<td align="center">'.obtenerTipoPoliza($row->tipo).'</td>
			<td align="center">'.obtenerPolizaPagada($row->tipo,$row->pagada).'</td>
			<td align="center">'.obtenerPolizaNombre($row->tipo,$polizas).$row->numero.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.$row->concepto.'</td>
			<td class="vinculos" style="text-align:left">
				&nbsp;
				<img src="'.base_url().'img/editar.png" title="Editar concepto" onclick="obtenerConcepto('.$row->idConcepto.')" />
				&nbsp;&nbsp;
				<img src="'.base_url().'img/borrar.png" title="Borrar concepto" onclick="borrarConcepto('.$row->idConcepto.')" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/cuentas.png" title="Transacciones" onclick="obtenerTransacciones('.$row->idConcepto.')" />
				<br />
				Editar
				Borrar
				Transacciones';
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>

	<div align="center">
		<ul id="pagination-digg" class="ajax-pagConceptosPolizas">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="erroresDatos">Aun no se han registrado pólizas</div>';
}
?>