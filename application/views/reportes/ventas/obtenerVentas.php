<script>
for(i=1;i<300;i++)
{
	$("#trProductos"+i).hide();
}
</script>
<?php
#if($ventas!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagVentas">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	<table class="admintable" width="100%">
	<tr>
		<th class="encabezadoPrincipal" colspan="4" align="right" style="border-right:none">
			Reporte de ventas  
		</th>
		<th class="encabezadoPrincipal" style="border-right:none; border-left:none" colspan="2">
			<img id="btnExportarPdfReporte" src="<?php echo base_url()?>img/pdf.png" width="22" title="PDF" onclick="reporteVentas()" />
			&nbsp;&nbsp;
			<img id="btnExportarExcelReporte" src="<?php echo base_url()?>img/excel.png" width="22" title="Excel" onclick="excelVentas()" />
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
        
        
        
        <th class="encabezadoPrincipal" colspan="2" align="right" style="border-right:none">
			Ventas por línea
		</th>
        
        <th class="encabezadoPrincipal" style="border-right:none; border-left:none" colspan="3">
			<img id="btnExportarPdfLinea" src="<?php echo base_url()?>img/pdf.png" width="22" title="PDF" onclick="reporteVentasLineas()" />
			&nbsp;&nbsp;
			<img id="btnExportarExcelLinea" src="<?php echo base_url()?>img/excel.png" width="22" title="Excel" onclick="excelVentasLineas()" />
			<br />
			<a>PDF</a>
			<a>Excel</a>
            
            <?php
            if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnExportarPdfLinea\');
					desactivarBotonSistema(\'btnExportarExcelLinea\');
				</script>';
			}
			?>
		</th>
        
        
        
        
        
		<th class="encabezadoPrincipal" colspan="4" style="border-left:none" align="right">
			Total: $<?php echo number_format($total,2)?>
		</th>
	</tr>
	<tr>
		<th>#</th>
		<th>
		Fecha
	   <!--  <?php
			  if($this->session->userdata('criterioVentas')=='a')
			  {
				echo '<a href="'.base_url().'reportes/ordenamientoVentas/z">
				<img src="'.base_url().'img/ocultar.png" width="17" /></a>';	
			  }
			  else
			  {
				  echo '<a href="'.base_url().'reportes/ordenamientoVentas/a">
				<img src="'.base_url().'img/mostrar.png" width="17" /></a>';
			  }
		  ?>-->
		</th>
		<th align="center">Cliente</th>
		<th align="center">Venta</th>
		<th align="center" valign="middle" >
			<?php
			echo '
			<select class="cajas" id="selectEstaciones" style="width:120px" onchange="obtenerVentas()">
				<option value="0">Estación</option>';

				foreach($estaciones as $row)
				{
					echo '<option '.($row->idEstacion==$idEstacion?'selected="selected"':'').' value="'.$row->idEstacion.'">'.$row->nombre.'</option>';
				}

			echo '</select>';

			?>
		</th>
		<th align="center">
		<?php 
			
			echo '
				<select id="selectZonas" class="cajas" style="width:110px" onchange="obtenerVentas()">
					<option value="0">'.$this->session->userdata('identificador').'</option>';
				
				foreach($zonas as $zona)
				{
					$seleccionado	=$zona['idZona']==$idZona?'selected="selected"':'';
					
					echo '<option '.$seleccionado.' value="'.$zona['idZona'].'">'.$zona['descripcion'].'</option>';
				}
					
				echo'</select>';
		?>
        </th>
		<th align="center">
        <?php 
			
			echo '
				<select id="selectAgentes" class="cajas" style="width:130px" onchange="obtenerVentas()">
					<option value="0">Agente de ventas</option>';
				
				foreach($usuarios as $row)
				{
					$seleccionado	=$row->idUsuario==$idUsuario?'selected="selected"':'';
					
					echo '<option '.$seleccionado.' value="'.$row->idUsuario.'">'.$row->nombre.'</option>';
				}
					
				echo'</select>';
		?>
        </th>
        <th align="center">CRM</th>
		<th align="center">
			<?php
			echo '
			<select class="cajas" id="selectFormas" style="width:120px" onchange="obtenerVentas()">
				<option value="0">Forma de pago</option>';

				foreach($formas as $row)
				{
					echo '<option '.($row->idForma==$idForma?'selected="selected"':'').' value="'.$row->idForma.'">'.$row->nombre.'</option>';
				}

			echo '</select>';

			?>
		</th>
		<th align="center">Subtotal</th>
		<th align="center">Descuento</th>
		<th align="center">Impuestos</th>
		<th align="center">Total</th>
        <th align="center">Abono</th>
        <th align="center">Saldo</th>
	</tr>
	<?php
		$i=1;
		$p=0;
		$total=0;
		
		foreach($ventas as $row)
		{
			$productos	= $this->reportes->obtenerProductosVentas($row->idCotizacion);
			$impuestos	= $this->reportes->obtenerProductosImpuestosVentas($row->idCotizacion);
			
			$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
			$cancelada	=0;
				
			if($row->idFactura!=0)
			{
				$cancelada	=$this->reportes->obtenerFacturaCancelada($row->idFactura);
			}
			
			if($cancelada==0)
			{
				$total		+=$row->total;
				
				/*$descuento	=$row->descuento>0?$row->subTotal*($row->descuento/100):0;
				$iva		=($row->subTotal-$descuento)*$row->iva;*/
						
				?>
					<tr <?php echo $estilo?> onclick="$('#trProductos<?php echo $i?>').toggle(1)">
						<td><?php echo $i?></td>
						<td align="center"><?php echo obtenerFechaMesCorto($row->fechaCompra)?></td>
						<td align="left"><?php echo $row->empresa?></td>
						<td align="left">
						<?php 
							echo $row->ordenCompra;
							echo $row->idTienda>0?'('.$row->tienda.')':'';
							echo ' <img src="'.base_url().'img/ventas.png" width="22" height="22" title="Ver detalles" onclick="obtenerVentaInformacion('.$row->idCotizacion.')" />';
						?></td>
						<td align="center"><?php echo $row->estacion ?> </td>
						<td align="center">
						<?php 
						
						echo $row->identificador
						?>
                        </td>
						<td align="left"><?php echo $row->usuario?></td>
                        
                        
                        <?php
						$seguimiento	= null;
						if(strlen($row->idSeguimiento)>0)
						{
							$seguimiento	= $this->crm->obtenerUltimoSeguimientoVenta($row->idCotizacion);
						}
						
						$mostrarSeguimiento=false;
			
						if($permisoCrm[0]->activo==1)
						{
							$mostrarSeguimiento=true;
						}
						
						echo'
						<td align="center" title="Click para ver detallles de seguimiento" '.($mostrarSeguimiento?($seguimiento!=null?'onclick="obtenerSeguimientoServicio('.$row->idCotizacion.','.$seguimiento->idSeguimiento.')"':'onclick="obtenerSeguimientoServicio('.$row->idCotizacion.',0)"'):'').' >';
							
							if($mostrarSeguimiento and $seguimiento!=null)
							{
								echo'
								<span >
									<div style="background-color: '.$seguimiento->color.'" class="circuloStatus"></div>
									<i style="font-weight:100">'.$seguimiento->status.'<br />'.obtenerFechaMesCortoHora($seguimiento->fecha).'</i>
								</span>';
							}
							if($mostrarSeguimiento and $seguimiento==null)
							{
								echo '<img src="'.base_url().'img/crm.png" width="22" height="22" />';
							}
							
						echo'
						</td>';
					 ?>
                        <td align="left"><?php echo $row->formaPago?></td>
						<td align="right">$<?php echo number_format($row->subTotal,2)?></td>
						<td align="right">$<?php echo number_format($row->descuento,2).' ( '.number_format($row->descuentoPorcentaje,2).'%)'?></td>
						<td align="right">$<?php echo number_format($row->iva,2); 
						
						if($impuestos!=null)
						{
							echo'(';
							$im=0;
							foreach($impuestos as $imp)
							{
								echo $im==0?number_format($imp->tasa,decimales).'%':', '.number_format($imp->tasa,decimales).'%';
								$im++;
							}
							
							echo')';
						}
						
						
						?></td>
						<td align="right">$<?php echo number_format($row->total,2)?></td>
                        <td align="right">$<?php echo number_format($row->pagado,2)?></td>
                        <td align="right">$<?php echo number_format($row->total-$row->pagado,2)?></td>
					</tr>
				<?php
				
				echo '
				<tr id="trProductos'.$i.'" >
					<td colspan="13">
					<table class="admintable" width="100%">
						<tr>
							<th>Producto</th>
							<th>Unidad</th>
							<th>Cantidad</th>
							<th>Precio</th>
							<th>Descuento</th>
							<th>Importe</th>
						</tr>';
					
					
					
					foreach($productos as $pro)
					{
						echo'
						<tr '.($p%2>0?'class="sinSombra"':'class="sombreado"').'>
							<td>'.$pro->nombre.'</td>
							<td>'.$pro->unidad.'</td>
							<td align="right">'.number_format($pro->cantidad,2).'</td>
							<td align="right">$'.number_format($pro->precio,2).'</td>
							<td align="right">$'.number_format($pro->descuento,2).'</td>
							<td align="right">$'.number_format($pro->importe,2).'</td>
						</tr>';
						
						$p++;
					}
					
					echo'
					</table>
					</td>
				</tr>';
				
				$i++;
			}
	}
	?>
	</table>
	<?php
}
/*else
{
	echo '<div class="Error_validar" style=" width:96%; margin-left:1.5%;">No hay registros de ventas</div>';
}*/

echo'
<div style="width:90%; margin-top:0%;">
	<ul id="pagination-digg" class="ajax-pagVentas">'.$this->pagination->create_links().'</ul>
</div>';

?>
