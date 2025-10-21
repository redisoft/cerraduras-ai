<?php
if($pronostico!=null)
{
	echo '
	<div id="generandoReporte"></div>
	<table class="admintable" width="100%">
		<tr>
			<th colspan="3" style="border-right:none" class="encabezadoPrincipal" align="right">
				Reporte de Pronóstico de pagos
			</th>
			<th class="encabezadoPrincipal" style="border-right:none; border-left:none">
				<img id="btnExportarPdfReporte" onclick="window.open(\''.base_url().'reportes/reportePronosticoGastos/'.$inicio.'/'.$fin.'/'.$idProveedor.'\')" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
				&nbsp;
				<img id="btnExportarExcelReporte" onclick="excelPronosticoGastos(\''.$inicio.'\',\''.$fin.'\','.$idProveedor.')" src="'.base_url().'img/excel.png" width="22" title="Excel" />
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
			<th align="right" style="border-left:none" class="encabezadoPrincipal">
				Total $'.number_format($gastos,2).'
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Proveedor</th>
			<th>Concepto</th>
			<th align="right">Importe</th>
		</tr>';
	
	$i=1;
	foreach($pronostico as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td align="center">';
			$proveedor	=$this->proveedores->obtenerProveedor($row->idProveedor);
			echo $proveedor!=null?$proveedor->empresa:'';
			echo'</td>
			<td>'.$row->producto.'</td>
			<td align="right">$'.number_format($row->pago,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de pronóstico</div>';
}