<?php
#if($inventarios!=null)
#{

	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagMateriaPrima">'.$this->pagination->create_links().'</ul>
	</div>';
	
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="4" class="encabezadoPrincipal" align="right" style="border-right:none"> 
				Inventario materia prima
			</th>
			<th  class="encabezadoPrincipal" style="border-right:none; border-left:none">
				<img id="btnExportarPdfReporte" onclick="reporteMateriaPrima()" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
				
				&nbsp;&nbsp;
				<img id="btnExportarExcelReporte" onclick="excelMateriaPrima()" src="'.base_url().'img/excel.png" width="22" title="Excel" />
				<br />
				<a>PDF</a>
    			<a>Excel</a> ';
			
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
			<th colspan="3" class="encabezadoPrincipal" style="border-left:none" align="right">Total: $'.number_format($total,2).'</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Código</th>
			<th>Materia prima </th>
			<th>Proveedor</th>
			<th>Unidad</th>
			<th>Existencia</th>
			<th>Costo unitario</th>
			<th>Valor total</th>
		</tr>';
	
	$i=$limite+1;
	foreach($materiaPrima as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		#onclick="obtenerInformacionCompras('.$row->idMaterial.')"
		echo '
		<tr '.$estilo.' onclick="obtenerInformacionMaterial('.$row->idMaterial.','.$row->idProveedor.')">
			<td align="right">'.$i.'</td>
			<td align="center">'.$row->codigoInterno.'</td>
			<td align="left">'.$row->nombre.'</td>
			<td align="center">'.$row->proveedor.'</td>
			<td align="center">'.$row->unidad.'</td>
			<td align="center">'.number_format($row->existencia,decimales).'</td>
			<td align="right">$'.number_format($row->costo,2).'</td>
			<td align="right">$'.number_format($row->existencia*$row->costo,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
	
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagMateriaPrima">'.$this->pagination->create_links().'</ul>
	</div>';
/*}
else
{
	echo '<div class="Error_validar">Sin registro de inventario</div>';
}*/