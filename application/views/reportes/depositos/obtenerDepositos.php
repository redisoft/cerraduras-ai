<?php

if($depositos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagDepositos">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th colspan="5" class="encabezadoPrincipal" style="border-right:none;">
				
				'.($emisor!=null?$emisor->nombre:'').'
				'.($cuenta!=null?'<br />'.$cuenta->banco.': '.$cuenta->cuenta.'':'').'
				'.($emisor!=null?'<br />'.$emisor->rfc:'').'
				<br />
				<img src="'.base_url().'img/pdf.png" width="22" onclick="reporteDepositos()" title="PDF" />
				<img src="'.base_url().'img/excel.png" width="22" onclick="excelDepositos()" title="Excel" />
			</th>
			
			<th align="right" class="encabezadoPrincipal" style="border-left:none">Total $'.number_format($totales,2).'</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Cliente</th>
			<th>Forma de pago</th>
			<th>Factura</th>
			<th>Importe</th>
			
		</tr>';
		
		$i=$limite;
		foreach($depositos as $row)
		{
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td align="right">'.$i.'</td>
				<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td align="left">'.$row->cliente.'</td>
				<td align="center">'.$row->formaPago.'</td>
				<td align="center">'.$row->factura.'</td>
				<td align="right">$'.number_format($row->pago,2).'</td>
				
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>
	
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagDepositos">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de depositos</div>';
}

?>