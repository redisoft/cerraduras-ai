<?php
#if($ingresos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;" align="center">
		<ul id="pagination-digg" class="pagination">'.$this->paginacion->create_links().'</ul>
	</div>';

	echo'
	<div class="table-responsive">
	<table class="table table-striped ">
		<tr>
			<th colspan="4" class="encabezadoPrincipal">
				Egresos
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Concepto</th>
			<th>Importe</th>
		</tr>';
	
	$i=$inicio;
	
	foreach($egresos as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td width="5%" align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td>'.$row->concepto.'</td>
			<td align="right">$'.number_format($row->pago,decimales).'</td>
		</tr>';

		$i++;
	}
	
	echo '</table></div>';
}
