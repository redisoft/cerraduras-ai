<?php
error_reporting(0);
echo '
<div id="generandoReporte"></div>

<div style="width:90%; margin-top:0%;">
	<ul id="pagination-digg" class="ajax-pagIngresos">'.$this->pagination->create_links().'</ul>
</div>
<table class="admintable" width="100%">
	<tr>
		<th colspan="2" style="border-right:none" class="encabezadoPrincipal" align="center">
			Reporte de caja
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
		
		<th class="encabezadoPrincipal" style="border-left:none" align="right"></th>
	</tr>
	<tr>
		<th width="4%">#</th>
		<th>Ticket</th>
		<th>Importe</th>
		<th>Hora</th>
	</tr>';
		
if($registros!=null)
{
	$totales=0;
	$i=1;
	foreach($registros as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';

		if($row->tipoRegistro>0)
		{
			$folio	=	obtenerFolioRegistro($row->tipoRegistro).configurarFolioTipo($row->folio);
			$totales-=$row->importe;
		}
		else
		{
			$folio	= $row->folio.' - '.$row->estacion;
			$totales+=$row->importe;
		}
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.$folio.'</td>
			<td align="right">$'.number_format($row->importe,2).'</td>
			<td align="center">'.obtenerHora($row->fecha).'</td>
		</tr>';
		
		$i++;
	}
	
	$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
	
	echo '
	<tr '.$estilo.'>
		<td colspan="2" align="right" class="totales">Total</td>

		<td align="right" class="totales">$'.number_format($totales,2).'</td>
		<td align="center"></td>
	</tr>';
	
	
}

echo '</table>
<div style="width:90%; margin-top:0%;">
	<ul id="pagination-digg" class="ajax-pagIngresos">'.$this->pagination->create_links().'</ul>
</div>';