<?php
if($auxiliar!=null)
{
	echo'
	<table class="admintable" width="100%">
		<tr>
			<th colspan="4" align="right" style="border-right:none" class="encabezadoPrincipal">';
			
			echo $proveedor!=null?$proveedor->empresa:'Todos los  proveedores';
			
			echo'
			</th>
			<th style="border-right:none; border-left:none" align="left" class="encabezadoPrincipal">
			<img id="btnExportarPdfReporte" src="'.base_url().'img/pdf.png" width="22" title="Imprimir" onclick="window.open(\''.base_url().'reportes/reporteAuxiliar/'.$inicio.'/'.$fin.'/'.$idProveedor.'\')" />
				&nbsp;&nbsp;
			<img id="btnExportarExcelReporte" src="'.base_url().'img/excel.png" width="22" title="Generar Excel Auxiliar" onclick="excelAuxiliarProveedores(\''.$inicio.'\',\''.$fin.'\','.$idProveedor.')" />
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
			<th align="right" class="encabezadoPrincipal"  style="border-left:none" >
				Total: $'.number_format($total,2).'
			</th>
		</tr>
		<tr>
			<th width="3%">#</th>
			<th width="8%">Fecha</th>
			<th width="13%">Num. Orden Comp.</th>
			<th width="20%">Factura</th>
			<th width="20%">Remisión</th>
			<th align="right">Monto</th>
		</tr>';
	
	$i		=1;
	$total	=0;
	foreach($auxiliar as $row)
	{
		$estilo	=$i%2>0?'class="sinSombra"':'class="sombreado"';
		#$monto	=$row->cantidad*$row->precio;
		$monto	=$row->monto;
		
		echo'
		<tr '.$estilo.'>
			<td align="center">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.$row->orden.'</td>
			<td align="center">'.($row->factura=='1'?$row->remision:'').'</td>
			<td align="center">'.($row->factura=='0'?$row->remision:'').'</td>
			<td align="right">$'.number_format($row->monto,2).'</td>
		</tr>';
		
		
		$i++;
	}
	
	echo '
	</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de auxiliar de proveedores</div';
}