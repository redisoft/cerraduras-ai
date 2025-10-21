<?php

if($registros!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagVentasEfectivo">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th>#</th>
			<th>Folio</th>
			<th>Estación</th>
			<th>Fecha</th>
			<th>Hora</th>
			<th style="text-align: right">Importe <br> $'.number_format($total,2).'</th>
			<th>Tipo</th>
		</tr>';
		
		$i=$limite;
		foreach($registros as $row)
		{
			echo '
			<tr '.($i%2==0?'class="sombreado"':'class="sinSombra"').'>
				<td align="right">'.$i.'</td>
				<td align="center">'.$row->folio.'</td>
				<td align="center">'.$row->estacion.'</td>
				<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td align="center">'.obtenerHora($row->fecha).'</td>
				<td align="right">$'.number_format($row->pago,2).'</td>
				<td align="center">'.($row->prefactura=='1'?'Prefactura':'Remisión').'</td>
				
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>
	
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagVentasEfectivo">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registros</div>';
}

?>