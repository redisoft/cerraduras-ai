<?php

if($retiros!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagRetiros">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th colspan="3" class="encabezadoPrincipal" style="border-right:none;">
				
				'.($emisor!=null?$emisor->nombre:'').'
				'.($cuenta!=null?'<br />'.$cuenta->banco.': '.$cuenta->cuenta.'':'').'
				'.($emisor!=null?'<br />'.$emisor->rfc:'').'
			</th>
			<th colspan="1" style="border-left:none; border-right:none;" class="encabezadoPrincipal">
				<img id="btnExportarPdfReporte" src="'.base_url().'img/pdf.png" width="22" onclick="reporteRetiros()" title="PDF" />
				&nbsp;
				<img id="btnExportarExcelReporte" src="'.base_url().'img/excel.png" width="22" onclick="excelRetiros()" title="Excel" />
				<br />
				PDF 
				Excel';
			
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnExportarPdfReporte\');
						desactivarBotonSistema(\'btnExportarExcelReporte\');
					</script>';
				}
			
			echo'   
			</th>
			
			<th align="right" class="encabezadoPrincipal" style="border-left:none" colspan="2">Total $'.number_format($totales,2).'</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Proveedor</th>
			<th>Forma de pago</th>
			<th>Factura</th>
			<th>Importe</th>
			
		</tr>';
		
		$i=$limite;
		foreach($retiros as $row)
		{
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td align="right">'.$i.'</td>
				<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td align="left">'.$row->proveedor.'</td>
				<td align="center">'.$row->formaPago.'</td>
				<td align="center">'.$row->factura.'</td>
				<td align="right">$'.number_format($row->pago,2).'</td>
				
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>
	
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagRetiros">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de retiros</div>';
}

?>