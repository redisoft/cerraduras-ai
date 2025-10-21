<?php
if($relacion!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagRelacionClientes">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th colspan="3" class="encabezadoPrincipal" style="border-right:none;">
				Relación clientes
				'.($emisor!=null?$emisor->nombre:'').'
				'.($emisor!=null?'<br />'.$emisor->rfc:'').'
			</th>
			<th class="encabezadoPrincipal" style="border-left:none; border-right:none;" colspan="2">
				<br />
				<img id="btnExportarPdfReporte" src="'.base_url().'img/pdf.png" width="22" onclick="reporteRelacionClientes()" title="PDF" />
				<img id="btnExportarExcelReporte" src="'.base_url().'img/excel.png" width="22" onclick="excelRelacionClientes()" title="Excel" />
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
			
			<th align="right" class="encabezadoPrincipal" style="border-left:none">Total: $'.number_format($totales,2).'</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Cliente</th>
			<th>RFC</th>
			<th>Subtotal</th>
			<th>Iva</th>
			<th>Total</th>
		</tr>';
		
		$i=$limite;
		foreach($relacion as $row)
		{
			$total	= $this->reportes->obtenerRelacionCliente($anio,$idEmisor,$row->idCliente);
			
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td align="right">'.$i.'</td>
				<td align="left">'.$row->cliente.'</td>
				<td align="left">'.$row->rfc.'</td>
				<td align="right">$'.number_format($total[0],2).'</td>
				<td align="right">$'.number_format($total[1],2).'</td>
				<td align="right">$'.number_format($total[2],2).'</td>
				
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>
	
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagRelacionClientes">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de relación de clientes</div>';
}

?>