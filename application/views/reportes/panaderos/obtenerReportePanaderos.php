<?php
#if($inventarios!=null)
#{

	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagPanaderos">'.$this->pagination->create_links().'</ul>
	</div>';
	
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="10" class="encabezadoPrincipal" align="center" style="border-right:none"> 
				<img id="btnExportarPdfReporte" onclick="reportePanaderos()" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
				
				&nbsp;&nbsp;
				<img id="btnExportarExcelReporte" onclick="excelPanaderos()" src="'.base_url().'img/excel.png" width="22" title="Excel" />
				<br />
				<a>PDF</a>
    			<a>Excel</a> 
			</th>
			
			<th colspan="6" class="encabezadoPrincipal" style="border-left:none" align="right">Total: $'.number_format($total,decimales).'</th>
		</tr>
		<tr>
			<th>#</th>
			<th>
				Fecha
				
				<img src="'.base_url().'img/'.($orden=='desc'?'mostrar.png':'ocultar.png').'" width="18" onclick="ordenReporte(\''.($orden=='desc'?'asc':'desc').'\')" />
			</th>
			<th width="17%">
				<select class="cajas" id="selectLineasPanaderos" name="selectLineasPanaderos" style="width:120px" onchange="obtenerReportePanaderos()">
					<option value="0">Línea</option>';
					
					foreach($lineas as $row)
					{
						echo '<option '.($row->idLinea==$idLinea?'selected="selected"':'').' value="'.$row->idLinea.'">'.$row->nombre.'</option>';
					}
					
				echo'
				</select>
			</th>
			<th>Orden</th>
			<th>Total producido</th>
			<th>Mano obra</th>
			
			<th>Maestro</th>
			
			<th>Maestro cuota sindical</th>
			<th>Maestro prima dominical</th>
			
			<th>Oficial</th>
			
			<th>Oficial cuota sindical</th>
			<th>Oficial prima dominical</th>
			
			<th>Cuota sindical</th>
			<th>Prima dominical</th>
			<th>Total</th>
		</tr>';
	
	$i=$limite+1;
	foreach($pedidos as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$reporte	= $this->pedidos->obtenerReportePedido($row->idPedido);
		$total		= $this->pedidos->obtenerTotalesPedido($row->idPedido);
		$impuestos	= $this->pedidos->obtenerImpuestosPedido($row->idPedido);
		
		$linea='';
		if($row->idLinea==2) $linea=frances;
		if($row->idLinea==3)  $linea=bizcocho;
		
		#onclick="obtenerInformacionCompras('.$row->idMaterial.')"
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fechaPedido).'</td>
			<td align="left">'.$row->linea.'</td>
			<td align="center">'.$linea.$row->folio.'</td>
			<!--<td align="right">'.number_format($row->producido,decimales).'</td>-->
			<td align="right">$'.number_format($total+$impuestos,decimales).'</td>
			<td align="right">$'.number_format($reporte!=null?$reporte->manoTotal:0,decimales).'</td>
			
			<td align="right">$'.number_format($reporte!=null?$reporte->maestro:0,decimales).'</td>
			
			<td align="right">$'.number_format($reporte!=null?$reporte->maestro*$reporte->cuotaSindical/100:0,decimales).'</td>
			<td align="right">$'.number_format($reporte!=null?$reporte->maestro*$reporte->primaDominical/100:0,decimales).'</td>
			
			<td align="right">$'.number_format($reporte!=null?$reporte->oficial:0,decimales).'</td>
			
			<td align="right">$'.number_format($reporte!=null?$reporte->oficial*$reporte->cuotaSindical/100:0,decimales).'</td>
			<td align="right">$'.number_format($reporte!=null?$reporte->oficial*$reporte->primaDominical/100:0,decimales).'</td>
			
			
			<td align="right">$'.number_format($reporte!=null?$reporte->cuotaTotal:0,decimales).'</td>
			<td align="right">$'.number_format($reporte!=null?$reporte->primaTotal:0,decimales).'</td>
			<td align="right">$'.number_format($reporte!=null?$reporte->manoTotal+$reporte->primaTotal-$reporte->cuotaTotal:0,decimales).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
	
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagPanaderos">'.$this->pagination->create_links().'</ul>
	</div>';
/*}
else
{
	echo '<div class="Error_validar">Sin registro de inventario</div>';
}*/