<?php
if($servicios!=null)
{
	echo '
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagVentaServicios">'.$this->pagination->create_links().'</ul>
	</div>
	
	<div id="generandoReporte"></div>
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" colspan="10" style="border-right:none">
				<img id="btnExportarPdfReporte" onclick="reporteVentaServicios()" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
				&nbsp;&nbsp;&nbsp;
				<img id="btnExportarExcelReporte" onclick="excelVentaServicios()" src="'.base_url().'img/excel.png" width="22" title="Excel" />
				<br />
				PDF
				&nbsp;&nbsp;
				Excel';
				
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
			
		</tr>
		<tr>
			<th width="2%">#</th>
			<th>Fecha</th>
			<th>Venta</th>
			<th>Servicio</th>
			<th>Cliente</th>
			<th>Periodicidad</th>
			<th>Plazo</th>
			<th>Número ciclos pagados</th>
			<th>Precio</th>
			<th>Acciones</th>
		</tr>';

	$i=$limite;
	foreach($servicios as $row)
	{
		$estilo			= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$ciclosPagados	= $this->reportes->obtenerCiclosPagados($row->idCotizacion,$row->idProducto);
		$ventas			= $this->reportes->obtenerVentaServiciosDetalle($row->idCotizacion,$row->idProducto);
		$subTotal		= $row->importe;
		$descuento		= $row->descuentoPorcentaje>0?$subTotal*($row->descuentoPorcentaje/100):0;
		$iva			= $row->ivaPorcentaje>0?($subTotal-$descuento)*($row->ivaPorcentaje/100):0;
		$total			= $subTotal-$descuento+$iva;
		
		echo '
		<tr '.$estilo.' onclick="$(\'#trProductos'.$i.'\').toggle(200)">
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fechaCompra).'</td>
			<td align="center">'.$row->ordenCompra.'</td>
			<td align="left">'.$row->servicio.'</td>
			<td align="left">'.$row->cliente.'</td>
			<td align="center">'.$row->periodicidad.'</td>
			<td align="center">'.$row->plazo.'</td>
			<td align="center">'.count($ciclosPagados).'</td>
			<td align="right">$'.number_format($total,decimales).'</td>
			<td align="center"></td>
		</tr>';
		
		echo '<tbody id="trProductos'.$i.'" style="display:none">';
		
		foreach($ventas as $ven)
		{
			echo '
			
			<tr>
				<td align="right"></td>
				<td align="center">'.obtenerFechaMesCorto($ven->fechaCompra).'</td>
				<td align="center">'.($ven->pendiente=='0'?$ven->ordenCompra:'').'</td>
				<td align="center" colspan="5">';
					
					echo($ven->cancelada=='1'?'<i>Cancelada</i>':'');
					
					if($ven->cancelada=='0')
					{
						if($ven->total==$ven->pagado) echo '<label style="color: red">Cobrado</label>';
						
						if($ven->total>$ven->pagado and $ven->pagado>0) echo '<label style="color: red">Cobrado parcialmente</label>';
					}
				
				echo'
				</td>
				<td align="right">$'.number_format($ven->total,decimales).'</td>
				<td align="center">';
				
					if($ven->cancelada=='0')
					{
						if($ven->pendiente=='0' and $ven->pagado==0)
						{
							echo '
							<img src="'.base_url().'img/editar.png" width="22" height="22" onclick="obtenerVentaServicioEditar('.$ven->idCotizacion.')" />
							&nbsp;&nbsp;&nbsp;';
						}
						
						echo'
						<img src="'.base_url().'img/pagos.png" width="22" height="22" onclick="obtenerPagosClientes('.$ven->idCotizacion.')" />
						&nbsp;&nbsp;
						
						<img src="'.base_url().'img/borrar.png" width="22" height="22" onclick="accesoCancelarVentaServicios('.$row->idCotizacion.','.$ven->idCotizacion.','.$ven->idProduct.')" />
						<br />';
						
						if($ven->pendiente=='0' and $ven->pagado==0)
						{
							echo'
							&nbsp;&nbsp;
							<a>Editar</a>';
						}
						
						echo'
						<a>Cobrar</a>
						<a>Cancelar</a>';
					}
					
					echo'
				</td>
			</tr>';
		}
		
		echo '</tbody>';
		
		$i++;
		
	}
	
	echo '</table>
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagVentaServicios">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de venta de servicios</div>';
}