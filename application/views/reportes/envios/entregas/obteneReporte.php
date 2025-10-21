<!--script src="<?php echo base_url()?>js/mostrar.js"></script-->

<?php
#if($ventas!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagReporteEntregas">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	
	<table class="admintable" width="100%" id="tablaEntregas">
		<tr>
			<th class="encabezadoPrincipal" colspan="7">
				Reporte de entregas
			</th>
		</tr>
		<tr>
			<th width="3%" align="right">#</th>
			<th>Fecha</th>
			<th>Folio</th>
			<th>Tienda</th>
			<th>Chofer</th>
			<th>Vehículo</th>
			<th>Acciones</th>
		</tr>
        
    <?php
	    
	$i		= $limite;
	foreach($registros as $row)
	{
		?>
		<tr>
			<td align="right"><?php echo $i?></td>
			<td align="center"><?php echo obtenerFechaMesCortoHora($row->fechaRegistro)?></td>
			<td align="center"><?php echo $row->folio?></td>
			<td align="left"><?php echo $row->tienda?></td>
			<td align="left"><?php echo $row->personal?></td>
			<td align="left"><?php echo $row->modelo.', '.$row->marca?></td>
			
			<td align="center">
			<?php
				echo '
				<img id="btnEntregas'.$i.'" onclick="formularioEntregasFolio('.$row->folio.','.$row->idPersonal.')"  src="'.base_url().'img/pver.png" width="20" height="20" title="Entregas"/>
				&nbsp;&nbsp;
				<a href="'.base_url().'reportes/pdfReporteEntregas/'.sha1($row->idTicket).'" target="blank_"><img id="btnImprimirReporte'.$i.'"  src="'.base_url().'img/pdf.png" width="20" height="20" title="Imprimir"/></a>
				<br />
				<a id="a-btnEntregas'.$i.'">Entregas</a>
				
				<a id="a-btnImprimirReporte'.$i.'">Imprimir</a>';
			
             ?>
             
			</td>
		</tr>
        
		<?php
		$i++;
	}
	
	?>
    </table>
	
    <?php
	
	echo'
	<div style="width:90%; margin-top:2px;">
		<ul id="pagination-digg" class="ajax-pagReporteEntregas">'.$this->pagination->create_links().'</ul>
	</div>';
}

?>
