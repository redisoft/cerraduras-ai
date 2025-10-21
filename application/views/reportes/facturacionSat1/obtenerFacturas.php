<?php
if($facturas!=null)
{
	echo '
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagFacturacionSat">'.$this->pagination->create_links().'</ul>
	 </div>
	<table class="admintable" width="100%">
		<tr>
			<th colspan="7" class="encabezadoPrincipal"> 
				Reporte de facturación SAT
				<!--<img onclick="reporteFacturacion()" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
				<img onclick="excelFacturacion()" src="'.base_url().'img/excel.png" width="22" title="Excel" />
				<img onclick="zipearFacturas()" src="'.base_url().'img/zip.png" width="22" title="Zipear" />-->
			</th>
		</tr>
		
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Serie y folio</th>
			<th>Folio fiscal</th>
			<th>Total</th>
			<th>Tipo</th>
			<th>Acciones</th>
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
			<td align="right">$'.number_format($row->total,2).'</td>
			<td align="center">'.($row->recibida=='1'?'Recibida':'Emitida').'</td>
			<td align="center">
				<img id="btnExportarPdfReporte'.$i.'" onclick="window.open(\''.base_url().'pdf/crearFacturaSat/'.$row->idFactura.'\')" src="'.base_url().'img/pdf.png" width="25" />
				&nbsp;
				<img id="btnExportarExcelReporte'.$i.'" title="Descargar xml" onclick="window.location.href=\''.base_url().'facturacion/descargarXMLSat/'.$row->idFactura.'\'"  src="'.base_url().'img/xml.png" width="25" style="cursor:pointer" />
				<br />
				<a id="a-btnExportarPdfReporte'.$i.'">PDF</a>
				<a id="a-btnExportarExcelReporte'.$i.'">XML</a>';
			
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnExportarPdfReporte'.$i.'\');
						desactivarBotonSistema(\'btnExportarExcelReporte'.$i.'\');
					</script>';
				}
			
			echo'   
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