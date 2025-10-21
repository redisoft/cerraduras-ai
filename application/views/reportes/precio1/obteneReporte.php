
<?php
#if($ventas!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagReporte">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	
	<table class="admintable" width="100%" >
		<tr>
			<th class="encabezadoPrincipal" colspan="5" style="border-right:none" align="right">
				Reporte de precio 1
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
			
			<th class="encabezadoPrincipal" align="right" colspan="5"  style="border-left:none">
				
			</th>
		</tr>
		<tr>
			<th class="">#</th>
			<th class="">Fecha</th>
			<th class="">Cliente</th>
			<th class="">Venta</th>
			<th class="">
				<?php
				echo '
				<select class="cajas" id="selectEstaciones" style="width:120px" onchange="obtenerReporte()">
					<option value="0">Estación</option>';

					foreach($estaciones as $row)
					{
						echo '<option '.($row->idEstacion==$idEstacion?'selected="selected"':'').' value="'.$row->idEstacion.'">'.$row->nombre.'</option>';
					}

				echo '</select>';

				?>
			</th>
			<th class="">Producto</th>
			<th class="">
				<?php 
				echo '
					<select id="selectAgentes" class="cajas" style="width:130px" onchange="obtenerReporte()">
						<option value="0">Agente</option>';
				
					foreach($usuarios as $row)
					{
						echo '<option '.($row->idUsuario==$idUsuario?'selected="selected"':'').' value="'.$row->idUsuario.'">'.$row->nombre.'</option>';
					}
					
				echo'</select>';
			?>
			</th>
			<th class="">Forma de pago</th>
			<th class="">Subtotal</th>
			<th class="">Impuestos</th>
			<th class="">Total</th>
		</tr>
        
    <?php
	    
	$i=1;
	$total=0;
	foreach($registros as $row)
	{
		$impuestos	= $row->importe*($row->ivaPorcentaje/100);
		?>
		<tr <?php echo $i%2>0?'class="sinSombra"':'class="sombreado"'?>>
			<td align="right"><?php echo $i?></td>
			<td align="center"><?php echo obtenerFechaMesCortoHora($row->fechaCompra)?></td>
			<td align="left"><?php echo $row->empresa?></td>
			<td align="center"><?php echo $row->folio?></td>
			<td align="center"><?php echo $row->estacion?></td>
			<td align="left"><?php echo $row->producto?></td>
			<td align="center"><?php echo $row->usuario?></td>
			<td align="center"><?php echo $row->formaPago?></td>
			<td align="right">$<?php echo number_format($row->importe,2)?></td>
			<td align="right">$<?php echo number_format($impuestos,2)?></td>
			<td align="right">$ <?php echo number_format($row->importe+$impuestos,2)?></td>
		</tr>

		<?php
		$i++;
	}
	
	?>
    </table>
    <?php
	
	echo'
	<div style="width:90%; margin-top:4%;">
		<ul id="pagination-digg" class="ajax-pagReporte">'.$this->pagination->create_links().'</ul>
	</div>';
}
/*else
{
	echo '<div class="Error_validar">Sin registros</div>';
}*/
?>
