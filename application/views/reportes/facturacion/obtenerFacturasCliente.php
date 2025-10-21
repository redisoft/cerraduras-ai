<?php
if($facturas!=null)
{
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagFactu">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%">
		<tr>
			<th colspan="4" class="encabezadoPrincipal" align="right" style="border-right:none"> 
				Reporte de facturación
			</th>
			<th  class="encabezadoPrincipal" style="border-right:none; border-left:none">
				<img id="btnExportarPdf" onclick="window.open(\''.base_url().'reportes/reporteFacturacion/'.$mes.'/'.$anio.'/'.$idCliente.'\')" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
				&nbsp;
				<img id="btnExportarExcel" onclick="excelFacturacion(\''.$mes.'\',\''.$anio.'\','.$idCliente.')" src="'.base_url().'img/excel.png" width="22" title="Excel" />
				&nbsp;
				<img id="btnZip" onclick="zipearFacturas(\''.$mes.'\',\''.$anio.'\','.$idCliente.')" src="'.base_url().'img/zip.png" width="22" title="Zipear" />
				
				<br />
				<a id="a-btnExportarPdf">PDF</a>
    			<a id="a-btnExportarExcel">Excel</a>   
				<a id="a-btnZip">Zip</a>';
				
			if($permiso[1]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnExportarPdf\');
					desactivarBotonSistema(\'btnExportarExcel\');
					desactivarBotonSistema(\'btnZip\');
				</script>';
			}
			
			echo'
			</th>
			<th colspan="3"  class="encabezadoPrincipal" style="border-left:none"></th>
		</tr>
		<tr>
			<th>#</th>
			<th>
			Fecha
			
			</th>
			<th>Documento</th>
			<th>Emisor</th>
			<th>Cliente</th>
			<th>Folio y serie</th>
			<th>Total</th>
			<th width="15%">Acciones</th>
		</tr>';
	
	$i=1;
	foreach($facturas as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$cancelada	= $row->cancelada==1?'<i> (Cancelada)</i>':'';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.$row->documento.'</td>
			<td align="left">'.$row->emisor.'</td>
			<td align="left">'.$row->empresa.'</td>
			<td align="center">'.$row->serie.$row->folio.$cancelada.'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
			<td align="center">';
			?>
                <a id="btnPdf<?php echo $i?>" onclick="window.open('<?php echo base_url()?>pdf/crearFactura/<?php echo $row->idFactura?>')" title="Ver factura en PDF" >
                    <img src="<?php echo base_url()?>img/pdf.png" width="22" />
                </a>

				<a id="btnXml<?php echo $i?>" title="Descargar xml" href="<?php echo base_url()?>facturacion/descargarXML/<?php echo $row->idFactura?>">
					<img src="<?php echo base_url()?>img/xml.png" width="25" style="cursor:pointer" />
				</a>

				<br />
				<a id="a-btnPdf<?php echo $i?>">PDF</a>
                &nbsp;&nbsp;
				<a id="a-btnXml<?php echo $i?>">XML</a>
				<?php
				if($permiso[1]->activo==0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnPdf'.$i.'\');
						desactivarBotonSistema(\'btnXml'.$i.'\');
					</script>';
				}
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
	
/*	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagFactu">'.$this->pagination->create_links().'</ul>
	</div>';*/
}
else
{
	echo '<div class="Error_validar">Sin registro de facturas</div>';
}