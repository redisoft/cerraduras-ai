<?php
echo '
<div id="generandoReporte"></div>

<div style="width:90%; margin-top:0%;">
	<ul id="pagination-digg" class="ajax-pagIngresos">'.$this->pagination->create_links().'</ul>
</div>
<table class="admintable" width="100%">
	<tr>
		<th colspan="7" style="border-right:none" class="encabezadoPrincipal" align="right">
			Reporte de ingresos
		</th>
		<th class="encabezadoPrincipal" style="border-right:none; border-left:none">
			<img id="btnExportarPdfReporte" onclick="window.open(\''.base_url().'reportes/reporteIngresos/'.$inicio.'/'.$fin.'/'.$idCuenta.'/'.$idDepartamento.'/'.$idProducto.'/'.$idGasto.'/'.$idCliente.'/'.$idIngreso.'/'.$criterio.'\')" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
			&nbsp;&nbsp;
			<img id="btnExportarExcelReporte" onclick="excelIngresos(\''.$inicio.'\',\''.$fin.'\','.$idCuenta.','.$idDepartamento.','.$idProducto.','.$idGasto.','.$idCliente.','.$idIngreso.')" src="'.base_url().'img/excel.png" width="22" title="Excel" />
				
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
		
		<th class="encabezadoPrincipal" colspan="5" style="border-left:none" align="right">Total $'.number_format($sumaIngresos,2).'</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Cliente</th>
		<th>
			<select class="cajas" id="selectProductos" name="selectProductos" style="width:150px" onchange="obtenerIngresos()"> 
				<option value="0">Concepto</option>';
			
			foreach($productos as $row)
			{
				$seleccionado	=$idProducto==$row->idProducto?'selected="selected"':'';
				echo '<option '.$seleccionado.' value="'.$row->idProducto.'">'.$row->nombre.'</option>';
			}
				
			echo'
			</select>
		</th>
		<th>Descripción del producto</th>
		<th>Venta</th>
		<th>
		<select class="cajas" id="selectDepartamentos" name="selectDepartamentos" style="width:150px" onchange="obtenerIngresos()"> 
				<option value="0">Departamento</option>';
			
			foreach($departamentos as $row)
			{
				$seleccionado	=$idDepartamento==$row->idDepartamento?'selected="selected"':'';
				echo '<option '.$seleccionado.' value="'.$row->idDepartamento.'">'.$row->nombre.'</option>';
			}
				
			echo'
			</select>
		</th>
		<th>
		<select class="cajas" id="selectGastos" name="selectGastos" style="width:150px" onchange="obtenerIngresos()"> 
				<option value="0">Tipo</option>';
			
			foreach($gastos as $row)
			{
				$seleccionado	=$idGasto==$row->idGasto?'selected="selected"':'';
				echo '<option '.$seleccionado.' value="'.$row->idGasto.'">'.$row->nombre.'</option>';
			}
				
			echo'
			</select>
		</th>
		
		<th>Factura/Remisión</th>
		<th>Subtotal</th>
		<th>Iva</th>
		<th>Total</th>
		<th width="12%">Acciones</th>
	</tr>';
		
if($ingresos!=null)
{
	$i=$limite;
	foreach($ingresos as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$factura	= $this->facturacion->obtenerFacturaCancelar($row->idFactura);
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.$row->cliente.'</td>
			<td align="center">';
			$producto	=$this->configuracion->obtenerProducto($row->idProducto);
			echo $producto!=null?$producto->nombre:'';
			echo'</td>
			<td>'.$row->producto.'</td>
			<td>';
				
				if($row->idVenta>0)
				{
					$cotizacion	=$this->clientes->obtenerCotizacion($row->idVenta);
					echo $cotizacion!=null?$cotizacion->ordenCompra:'';
					
					echo ' <img src="'.base_url().'img/ventas.png" width="22" height="22" title="Ver detalles" onclick="obtenerVentaInformacion('.$row->idVenta.')" />';
				}
				
			echo'</td>
			<td align="center">';
			$departamento	=$this->configuracion->obtenerDepartamento($row->idDepartamento);
			echo $departamento!=null?$departamento->nombre:'';
			echo'</td>
			<td align="center">';
			$gasto	=$this->configuracion->obtenerGasto($row->idGasto);
			echo $gasto!=null?$gasto->nombre:'';
			echo'</td>
			<td align="center">'.$row->factura.'</td>
			<td align="right">$'.number_format($row->subTotal,2).'</td>
			<td align="right">$'.number_format($row->ivaTotal,2).'</td>
			<td align="right">$'.number_format($row->pago,2).' '.($row->idFactura>0?'<br /><a tarjet="_blanck" title="Ver en PDF" href="'.base_url().'pdf/crearFactura/'.$row->idFactura.'" >CFDI: '.$factura->cfdi.'</a>':'').'</td>
			<td align="center">
				<img id="btnFacturar'.$i.'" src="'.base_url().'img/cfdi.png" width="22" height="22" onclick="formularioFacturaIngreso('.$row->idIngreso.')" title="Facturar" />';
				
				if($factura!=null)
				{
					if($factura->cancelada=='0')
					{
						echo '
						&nbsp;&nbsp;&nbsp;
						<img id="btnEnviarFactura'.$i.'"  src="'.base_url().'img/correo.png" title="Enviar CFDI" width="25" style="cursor:pointer" onclick="formularioCorreoFactura('.$row->idFactura.')"/>';
					}
				}
				
				echo'
				<br />
				
				<a id="a-btnFacturar'.$i.'">Factura</a>';
				
				if($factura!=null)
				{
					if($factura->cancelada=='0')
					{
						echo '&nbsp;<a id="a-btnEnviarFactura'.$i.'">Enviar</a>';
					}
				}
				
				
				if($permiso[2]->activo==0 or $row->idTraspaso!=0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnFacturar'.$i.'\');
						desactivarBotonSistema(\'btnEnviarFactura'.$i.'\');
					</script>';
				}
				
				if($factura!=null)
				{
					if($factura->cancelada=='0')
					{
						echo '
						<script>
							
							desactivarBotonSistema(\'btnFacturar'.$i.'\');
						</script>';
					}
				}
				
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	
}
#else
#{
#	echo '<div class="Error_validar">Sin registro de ingresos</div>';
#}

echo '</table>
<div style="width:90%; margin-top:0%;">
	<ul id="pagination-digg" class="ajax-pagIngresos">'.$this->pagination->create_links().'</ul>
</div>';