<?php
if($productos!=null)
{
	echo '
	 <div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagVenPro">'.$this->pagination->create_links().'</ul>
	 </div>
	<table class="admintable" width="100%">
		<tr>
			<th colspan="9">
			Reporte de ventas por producto
				<img onclick="window.open(\''.base_url().'reportes/reporteVentasProductos/'.$inicio.'/'.$fin.'/'.$idProducto.'\')" 
				src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
			
			<img onclick="excelVentasProductos(\''.$inicio.'\',\''.$fin.'\','.$idProducto.')" 
				src="'.base_url().'img/excel.png" width="22" title="Excel" />
			</th>
		</tr>
		
		<tr>
			<th width="">#</th>
			<th width="11%">Fecha</th>
			<th width="">Folio</th>
			<th width="10%">Código interno</th>
			<th width="20%">Producto</th>
			<th width="">Cantidad</th>
			<th width="">Precio</th>
			<th width="">Descuento</th>
			<th width="">Importe</th>
		</tr>';
	
	$i	=1;
	foreach($productos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td width="" align="right">'.$i.'</td>
			<td width="" align="center">'.$row->fechaCompra.'</td>
			<td width="" align="center">'.$row->folio.'</td>
			<td align="left">'.$row->codigoInterno.'</td>
			<td width="" align="left">'.$row->producto.'</td>
			<td width="" align="center">'.number_format($row->cantidad,2).'</td>
			<td width="" align="right">$'.number_format($row->precio,2).'</td>
			<td width="" align="right">$'.number_format($row->descuentoMonto,2).'</td>
			<td width="" align="right">$'.number_format($row->importe-$row->descuentoMonto,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagVenPro">'.$this->pagination->create_links().'</ul>
	 </div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de ventas por producto</div>';
}
?>