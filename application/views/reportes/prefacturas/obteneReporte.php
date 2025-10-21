
<?php
#if($ventas!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagPrefacturas">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	
	<table class="admintable" width="100%" >
		<tr>
			<th class="encabezadoPrincipal" colspan="4" style="border-right:none" align="right">
				Reporte de Remisión/Prefactura
			</th>
			<th style="border-right:none; border-left:none" class="encabezadoPrincipal">
                <img id="btnExportarPdfReporte" src="<?php echo base_url()?>img/pdf.png" width="22" title="PDF" onclick="pdfReporte()" />
                &nbsp;&nbsp;
                <img id="btnExportarExcelReporte" src="<?php echo base_url()?>img/excel.png" width="22" title="Excel" onclick="excelReporte()" />
				
                <br />
                <a>PDF</a>
                <a>Excel</a> 
                
                 <?php
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnExportarPdfReporte\');
						desactivarBotonSistema(\'btnExportarExcelReporte\');
					</script>';
				}
				?>           
			</th>
			
			<th class="encabezadoPrincipal" align="right" colspan="2"  style="border-left:none">
				
			</th>
		</tr>
		<tr>
			<th class="">#</th>
			<th class="">Fecha prefactura</th>
			<th class="">Prefactura</th>
			<th class="">Fecha remisión</th>
			<th class="">Remisión</th>
			<th class="" align="center">Cliente</th>
			<th class="" align="center">Total</th>
		</tr>
        
    <?php
	    
	$i=1;
	$total=0;
	foreach($registros as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		?>
		<tr <?php echo $estilo?>>
			<td align="right"><?php echo $i?></td>
			<td align="center"><?php echo obtenerFechaMesCortoHora($row->fechaCompra)?></td>
			<td align="center"><?php echo $row->folio?></td>
			<td align="center"><?php echo obtenerFechaMesCortoHora($row->fechaRemision)?></td>
			<td align="center"><?php echo $row->folioRemision?></td>
			<td align="left"><?php echo $row->empresa?></td>
			<td align="right">$ <?php echo number_format($row->total,2)?></td>
		</tr>

		<?php
		$i++;
	}
	
	?>
    </table>
    <?php
	
	echo'
	<div style="width:90%; margin-top:4%;">
		<ul id="pagination-digg" class="ajax-pagPrefacturas">'.$this->pagination->create_links().'</ul>
	</div>';
}
/*else
{
	echo '<div class="Error_validar">Sin registros</div>';
}*/
?>
