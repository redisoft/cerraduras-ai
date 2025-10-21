<?php
if($ingresos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagIngresosFacturados">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th colspan="4" class="encabezadoPrincipal" style="border-right:none;">
				
				'.($emisor!=null?$emisor->nombre:'').'
				'.($cuenta!=null?'<br />'.$cuenta->banco.': '.$cuenta->cuenta.'':'').'
				'.($emisor!=null?'<br />'.$emisor->rfc:'').'
			</th>
			<th colspan="2" class="encabezadoPrincipal" style="border-left:none; border-right:none;">
				<img id="btnExportarPdfReporte" src="'.base_url().'img/pdf.png" width="22" onclick="reporteIngresosFacturados()" title="PDF" />
				&nbsp;
				<img id="btnExportarExcelReporte" src="'.base_url().'img/excel.png" width="22" onclick="excelIngresosFacturados()" title="Excel" />
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
			
			<th colspan="2" align="right" class="encabezadoPrincipal" style="border-left:none">Total $'.number_format($totales,2).'</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Fecha de pago</th>
			<th>Cliente</th>
			<th>Factura</th>
			<th>Subtotal</th>
			<th>Iva</th>
			<th>Total</th>
			
		</tr>';
		
		$i=$limite;
		foreach($ingresos as $row)
		{
			$subTotal	= $row->pago/(1+$row->iva);
			$iva		= $row->iva*$subTotal;
			
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td align="right">'.$i.'</td>
				<td align="center">'.obtenerFechaMesCorto($row->fechaFactura).'</td>
				<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td align="left">'.$row->cliente.'</td>
				<!--<td align="center" onclick="obtenerDetallesFacturas('.$row->idFactura.')"><label>'.(strlen($row->facturaIngreso)>0?$row->facturaIngreso:$row->factura).'</label></td>-->
				<td align="center">'.(strlen($row->facturaIngreso)>0?$row->facturaIngreso:$row->factura).'</td>
				
				<td align="right">$'.number_format($subTotal,2).'</td>
				<td align="right">$'.number_format($iva,2).'</td>
				<td align="right">$'.number_format($row->pago,2).'</td>
				
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>
	
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagIngresosFacturados">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de ingresos facturados</div>';
}

?>