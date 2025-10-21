<?php
echo '
<div id="generandoReporte"></div>

<div style="width:90%; margin-top:0%;">
	<ul id="pagination-digg" class="ajax-pagIngresos">'.$this->pagination->create_links().'</ul>
</div>
<table class="admintable" width="100%">
	<tr>
		<th colspan="8" style="border-right:none" class="encabezadoPrincipal" align="right">
			Reporte de ingresos
		</th>
		<th class="encabezadoPrincipal" style="border-right:none; border-left:none">
			<img id="btnExportarPdfReporte" onclick="reporteIngresos()" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
			&nbsp;&nbsp;
			<img id="btnExportarExcelReporte" onclick="excelIngresos(\''.$inicio.'\',\''.$fin.'\','.$idCuenta.','.$idDepartamento.','.$idProducto.','.$idGasto.','.$idIngreso.')" src="'.base_url().'img/excel.png" width="22" title="Excel" />
				
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
		
		<th class="encabezadoPrincipal" colspan="6" style="border-left:none" align="right">Total $'.number_format($sumaIngresos,2).'</th>
	</tr>
	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>'.(sistemaActivo=='IEXE'?'Alumno':'Cliente').'</th>
		<th>Matrícula</th>
		<th>Descripción</th>
		<th>Forma de pago</th>
		<th>Folio</th>
		<th>Banco</th>
		<th>Cuenta</th>
		<th>Factura</th>
		<th>Remisión</th>
		<th>Subtotal</th>
		<th>Impuestos</th>
		<th>Total</th>
		'.(sistemaActivo!='pinata'?'<th width="12%">Acciones</th>':'').'
		
		<th style="display:none">
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
		
		<th style="display:none">
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
		
		<th style="display:none">
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
	</tr>';
		
if($ingresos!=null)
{
	$i=$limite;
	foreach($ingresos as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$factura	= $this->facturacion->obtenerFacturaCancelar($row->idFactura);
		$banco 		= explode('|',$row->banco);
		$factura	= $this->facturacion->obtenerFacturaCancelar($row->idFactura);
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.$row->cliente.'</td>
			<td align="center">'.$row->matricula.'</td>
			
			<td>'.(strlen($row->productoCatalogo)>1?$row->productoCatalogo:$row->producto).'</td>
			<td>'.$row->forma.'</td>
			<td>'.$row->cheque.$row->transferencia.'</td>
			<td align="center">'.(strlen($row->banco)>3?$banco[1]:'').'</td>
			<td align="center">'.(strlen($row->banco)>3?$banco[0]:'').'</td>
			
			<td align="center">'.($row->remision=='0'?($factura!=null?$factura->cfdi:$row->factura):'').'</td>
			<td align="center">'.($row->remision=='1'?$row->factura:'').'</td>
			
			
			<td align="right">$'.number_format($row->subTotal,2).'</td>
			<td align="right">$'.number_format($row->ivaTotal,2).'</td>
			<td align="right">$'.number_format($row->pago,2).' '.($row->idFactura>0?'<br /><a tarjet="_blanck" title="Ver en PDF" href="'.base_url().'pdf/crearFactura/'.$row->idFactura.'" >CFDI: '.$factura->cfdi.'</a>':'').'</td>';
			
			if(sistemaActivo!='pinata')
			{
				echo'
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
				</td>';
			}
		
		echo'
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