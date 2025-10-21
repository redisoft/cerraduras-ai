<?php
if($cajaChica!=null)
{
	$sumaCaja	=$this->reportes->sumarReporteCajaChica($mes,$anio,$criterio);
	
	 echo'
	 <div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagCaja">'.$this->pagination->create_links().'</ul>
	 </div>';
	
	echo'
	<script>
		$("#tablaEgresos tr:even").addClass("arriba");
		$("#tablaEgresos tr:odd").addClass("abajo");
	</script>
	<table class="admintable" id="tablaEgresos" width="100%">
		<tr>
			<th class="encabezadoPrincipal" align="right" style="border-right:none" colspan="6">
				Reporte de caja chica
			</th>
			<th class="encabezadoPrincipal" style="border-right:none; border-left:none">
				<img id="btnExportarPdfReporte" src="'.base_url().'img/pdf.png" width="22" title="Generar PDF" onclick="window.open(\''.base_url().'reportes/reporteCajaChica/'.$mes.'/'.$anio.'/'.$criterio.'\')" />
				&nbsp;
				<img id="btnExportarExcelReporte" onclick="excelCajaChica(\''.$mes.'\',\''.$anio.'\')" src="'.base_url().'img/excel.png" width="22" title="Excel" />
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
			<th class="encabezadoPrincipal" style="border-left:none" colspan="3" align="right">
				Suma caja chica: $'.number_format($sumaCaja,2).'
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>
				Concepto
				
			</th>
			<th>Monto</th>
			<th>Forma de pago</th>
			<th>Cheque / Trasferencia</th>
			<th>Nombre</th>
			<th>Departamento</th>
			<th>Descripción del producto</th>
			<th>Tipo</th>
		</tr>';
	
	$i=1;
	
	foreach($cajaChica as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo'
		<tr '.$estilo.'>
			<td class="vinculos">'.$i.'</td>
			<td class="vinculos">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td>';
				$producto	=$this->configuracion->obtenerProducto($row->idProducto);
				echo $producto!=null?$producto->nombre:'';
				
			echo'</td>
			<td class="vinculos" align="right">$'.number_format($row->pago,2).'</td>
			<td class="vinculos">'.$row->formaPago.'</td>
			<td class="vinculos" align="center">'.$row->cheque.$row->transferencia.'</td>
			<td>';
				$nombre	=$this->administracion->obtenerNombre($row->idNombre);
				echo $nombre!=null?$nombre->nombre:'';
				
			echo'</td>
			<td>';
				$departamento	=$this->configuracion->obtenerDepartamento($row->idDepartamento);
				echo $departamento!=null?$departamento->nombre:'';
				
			echo'</td>
			<td class="vinculos">';
			echo $row->producto;
			
			echo'
			</td>
			<td>';
				$gasto	=$this->configuracion->obtenerGasto($row->idGasto);
				echo $gasto!=null?$gasto->nombre:'';
			echo'</td>
		</tr>';
		
		$cajas=$this->administracion->obtenerCajaChica($row->idEgreso);
		
		foreach($cajas as $caja)
		{
			echo'
			<tr>
				<td class="vinculos"></td>
				<td class="vinculos">'.obtenerFechaMesCorto($caja->fecha).'</td>
				<td class="vinculos" class="vinculos">'.$caja->concepto.'</td>
				<td class="vinculos" align="right">$'.number_format($caja->importe,2).'</td>
				<td class="vinculos"></td>
				<td class="vinculos"></td>
				<td class="vinculos"></td>
				<td class="vinculos"></td>
				<td class="vinculos"></td>
				<td class="vinculos"></td>
			</tr>';
		}
		
		$i++;
	}
	
	echo '</table>';
	
	 echo'
	 <div style="width:90%;">
		<ul id="pagination-digg" class="ajax-pagCaja">'.$this->pagination->create_links().'</ul>
	 </div>';
}
else
{
	 echo'
	 <div class="Error_validar" style="margin-top:2px; width:95%; float:left margin-bottom: 5px;">
		Sin registros de caja chica.
	 </div>';
}