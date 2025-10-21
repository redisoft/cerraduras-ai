<?php
if($gastos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagGastosFacturados">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th colspan="5" class="encabezadoPrincipal" style="border-right:none;">
				
				
				'.($emisor!=null?$emisor->nombre:'').'
				'.($emisor!=null?'<br />'.$emisor->rfc:'').'
			</th>
			<th class="encabezadoPrincipal" style="border-left:none; border-right:none;" colspan="2">
				<img id="btnExportarPdfReporte" src="'.base_url().'img/pdf.png" 	width="22" onclick="reporteGastosFacturados()" title="PDF" />
				&nbsp;
				<img id="btnExportarExcelReporte" src="'.base_url().'img/excel.png" 	width="22" onclick="excelGastosFacturados()" title="Excel" />
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
			<th align="right" class="encabezadoPrincipal" style="border-left:none; border-right:none;" colspan="1">$'.number_format($totales[1],2).'</th>
			<th align="right" class="encabezadoPrincipal" style="border-left:none" colspan="1">$'.number_format($totales[0],2).'</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Fecha pago</th>
			<th>Emisor</th>
			<th>Proveedor</th>
			<th>Factura</th>
			<th>Subtotal</th>
			<th>Iva</th>
			<th width="15%">Total</th>
		</tr>';
		
		$i	= $limite;
		foreach($gastos as $row)
		{
			#$total			+= $row->pago;
			$iva			= 1+($row->iva/100);
			$subTotal		= $row->pago/$iva;
			$iva			= $row->pago-$subTotal;
			
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td align="right">'.$i.'</td>
				<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
				<td align="left">'.$row->emisor.'</td>
				<td align="left">'.$row->empresa.'</td>
				<td align="left">'.$row->factura.'</td>
				<td align="right">$'.number_format($subTotal,2).'</td>
				<td align="right">$'.number_format($iva,2).'</td>
				<td align="right">$'.number_format($row->pago,2).'</td>
				
			</tr>';
			
			$i++;
		}
	
	echo'
	</table>
	
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagGastosFacturados">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de gastos facturados</div>';
}

?>