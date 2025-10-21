<!--script src="<?php echo base_url()?>js/mostrar.js"></script-->

<?php
#if($ventas!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagReporteInventario">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	
	<table class="admintable" width="100%" id="tablaInventario">
		<tr>
			<th class="encabezadoPrincipal" colspan="9">
				<img id="btnExportarPdf" src="<?php echo base_url()?>img/pdf.png" width="22" title="PDF" onclick="pdfInventario()" />
                &nbsp;&nbsp;
                <img id="btnExportarExcel" src="<?php echo base_url()?>img/excel.png" width="22" title="Excel" onclick="excelInventario()" />

				<br />
                <a>PDF</a>
                <a>Excel</a> 
			</th>
		</tr>
		<tr>
			<th width="3%" align="right">#</th>
			<th>Fecha</th>
			<th>Nota</th>
			<th>Folio</th>
			<th>Código interno</th>
			<th>Producto</th>
			<th>Entregado</th>
			<th>No entregado</th>
			<th>Comentarios</th>
		</tr>
        
    <?php
	    
	$i		= 1;
	foreach($registros as $row)
	{
		?>
		<tr>
			<td align="right"><?php echo $i?></td>
			<td align="center"><?php echo obtenerFechaMesCortoHora($row->fechaCompra)?></td>
			<td align="center"><?php echo $row->estacion.$row->folio?></td>
			<td align="center"><?php echo $row->folioTicket?></td>
			<td align="left"><?php echo $row->codigoInterno?></td>
			<td align="left"><?php echo $row->producto?></td>
			<td align="center"><?php echo round($row->cantidadEntregada,2)?></td>
			<td align="center"><?php echo round($row->noEntregados,2)?></td>
			<td align="left"><?php echo nl2br($row->comentarios)?></td>
		</tr>
        
		<?php
		$i++;
	}
	
	?>
    </table>
	
    <?php
	
	echo'
	<div style="width:90%; margin-top:2px;">
		<ul id="pagination-digg" class="ajax-pagReporteInventario">'.$this->pagination->create_links().'</ul>
	</div>';
}

?>
