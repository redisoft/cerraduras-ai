<?php

if(!empty($compras))
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagin">'.$this->pagination->create_links().'</ul>
	</div>';
	?>

	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal">#</th>
			<th class="encabezadoPrincipal">Fecha</th>
            <th class="encabezadoPrincipal">Compra</th>
			<th class="encabezadoPrincipal">Proveeedor</th>
			<th class="encabezadoPrincipal">Orden de compra</th>
			<th class="encabezadoPrincipal">Precio</th>
			<th class="encabezadoPrincipal">Pago</th>
			<th class="encabezadoPrincipal">Saldo</th>
			<th class="encabezadoPrincipal" style="width:10%">Acciones</th>             
		</tr>
	<?php
	$i=1;
	foreach ($compras as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$pagado		= $this->compras->obtenerPagado($row->idCompras);
		$saldo		= $row->total-$pagado;
		$onclick	= 'onclick="obtenerComprita('.$row->idCompras.')" title="Click para ver el detalle"';
		
		?>
	
		<tr <?php echo $estilo?>>
			<td align="left" valign="middle" <?php echo $onclick?>> <?php echo $i ?> </td>
			<td align="center" valign="middle" <?php echo $onclick?>><?php echo obtenerFechaMesCorto($row->fechaEntrega); ?></td>
            <td align="center" valign="middle" <?php echo $onclick?>>  
			<?php
			$pdf	= "comprasPDF";
			$link	= "compras/administracion/fecha/".$row->idCompras;
			if($row->reventa=='1') 		{echo 'Productos'; 			$pdf	= "comprasPDFProductos"; 	$link	= "compras/productos/fecha/".$row->idCompras;}
			if($row->inventario=='1') 	{echo 'Mobiliario/equipo'; 	$pdf	= "comprasPDFInventarios"; 	$link	= "compras/inventarios/fecha/".$row->idCompras;}
			if($row->servicios=='1') 	{echo 'Servicios'; 			$pdf	= "comprasPDFServicios"; 	$link	= "servicios/compras/fecha/".$row->idCompras;}
			if($row->servicios=='0' and $row->inventario=='0' and $row->reventa=='0') {echo 'Materia prima'; $pdf	= "comprasPDF";}
			?> 
            </td>
			<td align="center" valign="middle" <?php echo $onclick?>>  <?php print($row->empresa); ?> </td>
			<td align="center" valign="middle" <?php echo $onclick?>> <a><?php print($row->nombre); ?> </a></td>
			<td align="right" valign="middle" <?php echo $onclick?>>  $<?php print(number_format($row->total,2)); ?> </td>
			<td id="tdPagado<?php echo $row->idCompras?>" align="right" valign="middle" <?php echo $onclick?>>$<?php print(number_format($pagado,2))?></td>
			<td id="tdSaldo<?php echo $row->idCompras?>" align="right" valign="middle" <?php echo $onclick?>>$<?php print(number_format($saldo,2))?></td>
			<td align="center"   valign="middle"> 
			<?php
			
			/*$pdf	= "comprasPDF";
			
			if($row->reventa=='1') 		$pdf	= "comprasPDFProductos";
			if($row->inventario=='1') 	$pdf	= "comprasPDFInventarios";
			if($row->servicios=='1') 	$pdf	= "comprasPDFServicios";
			if($row->servicios=='0' and $row->inventario=='0' and $row->reventa=='0') $pdf	= "comprasPDF";*/
			
			echo '
			<img onclick="window.open(\''.base_url().'compras/'.$pdf.'/'.$row->idCompras.'/'.$this->session->userdata('idLicencia').'\')" src="'.base_url().'img/pdf.png" width="22" height="22" title="PDF" />
			&nbsp;
			<a href="'.base_url().$link.'">
				<img src="'.base_url().'img/compras.png" width="22" height="22" title="PDF" />
			</a>
			&nbsp;&nbsp;
			<br />
			<a id="a-btnPdfCompras'.$i.'">PDF</a>

			<a id="a-btnPdfCompras'.$i.'">Detalles</a>';
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
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagin">'.$this->pagination->create_links().'</ul>
	</div>';
	
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de compras</div>';
}
?>