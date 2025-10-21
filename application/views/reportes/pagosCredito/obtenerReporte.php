<?php
echo '
<div id="generandoReporte"></div>

<div style="width:90%; margin-top:0%;">
	<ul id="pagination-digg" class="ajax-pagIngresos">'.$this->pagination->create_links().'</ul>
</div>
<table class="admintable" width="100%">
	<tr>
		<th colspan="4" style="border-right:none" class="encabezadoPrincipal" align="right">
			Reporte de pago créditos
		</th>
		<th class="encabezadoPrincipal" style="border-right:none; border-left:none">
			<img id="btnExportarPdfReporte" onclick="reportePdf()" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
			&nbsp;&nbsp;
			<img id="btnExportarExcelReporte" onclick="reporteExcel()" src="'.base_url().'img/excel.png" width="22" title="Excel" />
				
			<br />
			<a>PDF</a>
			<a>Excel</a>';
			
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
		
		<th class="encabezadoPrincipal" colspan="5" style="border-left:none" align="right">Total $'.number_format($totales,2).'</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Cliente</th>
		<th>Nota</th>
		<th>Forma de pago</th>
		<th>Banco</th>
		<th>Cuenta</th>
		<th>Factura</th>
		<th>Total venta</th>
		<th>Pago</th>
	</tr>';
		
if($ingresos!=null)
{
	$i=$limite;
	foreach($ingresos as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$factura	= $this->facturacion->obtenerFacturaCancelar($row->idFactura);
		$banco 		= explode('|',$row->banco);
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.$row->cliente.'</td>
			<td>'.$row->estacion.'-'.$row->folio.'</td>
			<td>'.$row->forma.'</td>
			<td align="center">'.(strlen($row->banco)>3?$banco[1]:'').'</td>
			<td align="center">'.(strlen($row->banco)>3?$banco[0]:'').'</td>
			<td align="center">'.($factura!=null?$factura->cfdi:$row->factura).'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
			<td align="right">$'.number_format($row->pago,2).'</td>
		</tr>';
		
		$i++;
	}
	
	
}

echo '</table>
<div style="width:90%; margin-top:0%;">
	<ul id="pagination-digg" class="ajax-pagIngresos">'.$this->pagination->create_links().'</ul>
</div>';