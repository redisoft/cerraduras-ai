<?php
if($facturas!=null)
{
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagRecibos">'.$this->pagination->create_links().'</ul>
	</div>';
	
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="7" class="encabezadoPrincipal"> 
				Recibos de nómina
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Emisor</th>
			<th>Empleado</th>
			<th>Folio y serie</th>
			<th>Total</th>
			<th width="15%">Acciones</th>
		</tr>';
	
	$i=1;
	foreach($facturas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cancelada	=$row->cancelada==1?'<i> (Cancelada)</i>':'';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="left">'.$row->emisor.'</td>
			<td align="left">'.$row->empleado.'</td>
			<td align="center">'.$row->serie.$row->folio.$cancelada.'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
			
			<td align="center">
				<a onclick="window.open('.base_url().'pdf/reciboNomina/'.$row->idFactura.')" title="Ver recibo en PDF" >
					<img src="'.base_url().'img/pdf.png" width="25" />
				</a>
                &nbsp;
                <a title="Descargar xml" href="'.base_url().'facturacion/descargarXML/'.$row->idFactura.'">
					<img src="'.base_url().'img/xml.png" width="25" style="cursor:pointer" />
				</a>
				<br />
				<a>PDF</a>&nbsp;&nbsp;
				<a>XML</a>&nbsp;&nbsp;';

			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
	
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagRecibos">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de recibos</div>';
}