<?php
if($facturas!=null)
{
	echo '
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagFacturacionSat">'.$this->pagination->create_links().'</ul>
	 </div>
	<table class="admintable" width="100%">
		<tr>
			<th colspan="9" class="encabezadoPrincipal"> 
				Reporte de facturación SAT
				<!--<img onclick="reporteFacturacion()" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
				<img onclick="excelFacturacion()" src="'.base_url().'img/excel.png" width="22" title="Excel" />
				-->
				
				<img onclick="zipearFacturasSat()" src="'.base_url().'img/zip.png" width="22" title="Zipear" />
			</th>
		</tr>
		
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Serie y folio</th>
			<th>Folio fiscal</th>
			<th>Emisor</th>
			<th>Receptor</th>
			<th>Total</th>
			<th>Tipo</th>
			<th width="10%">Acciones</th>
		</tr>';
	
	$i=$limite+1;
	foreach($facturas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';

		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.$row->serie.$row->folio.'</td>
			<td align="center">'.$row->uuid.'</td>
			<td align="center">'.$row->emisor.'</td>
			<td align="center">'.$row->receptor.'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
			<td align="center">'.($row->recibida=='1'?'Recibida':'Emitida').'</td>
			<td align="center">
				<img onclick="window.open(\''.base_url().'pdf/crearFacturaSat/'.$row->idFactura.'\')" src="'.base_url().'img/pdf.png" width="25" />
				
				<a title="Descargar xml" href="'.base_url().'reportes/descargarXMLSat/'.$row->idFactura.'">
					<img src="'.base_url().'img/xml.png" width="25" style="cursor:pointer" />
				</a>
				<br />
				<a>PDF</a>
				<a>XML</a>
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagFacturacionSat">'.$this->pagination->create_links().'</ul>
	 </div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de facturas</div>';
}