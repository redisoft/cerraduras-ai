<?php
echo '
<div id="generandoReporte"></div>
<div style="width:90%; margin-top:0%;">
	<ul id="pagination-digg" class="ajax-pagGastos">'.$this->pagination->create_links().'</ul>
</div>

<table class="admintable" width="100%">
	<tr>
		<th colspan="7" style="border-right:none" class="encabezadoPrincipal" align="right">
			Reporte de egresos
		</th>
		<th class="encabezadoPrincipal" style="border-right:none; border-left:none">
			<img id="btnExportarPdfReporte" onclick="window.open(\''.base_url().'reportes/reporteGastos/'.$inicio.'/'.$fin.'/'.$idCuenta.'/'.$idDepartamento.'/'.$idProducto.'/'.$idGasto.'/'.$idProveedor.'/'.$criterio.'\')" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
			&nbsp;&nbsp;
			<img id="btnExportarExcelReporte" onclick="excelGastos(\''.$inicio.'\',\''.$fin.'\','.$idCuenta.','.$idDepartamento.','.$idProducto.','.$idGasto.','.$idProveedor.')" src="'.base_url().'img/excel.png" width="22" title="Excel" />
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
		<th colspan="6" align="right" style="border-left:none" class="encabezadoPrincipal"> Total $'.number_format($sumaGastos,2).'</th>
	</tr>

	<tr>
		<th>#</th>
		<th>Fecha</th>
		<th>Proveedor</th>
		<th style="display:none">
			<select class="cajas" id="selectProductos" style="width:150px" onchange="obtenerGastos()"> 
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
		
		<th>Forma de pago</th>
		<th>Folio</th>
		<th>Banco</th>
		<th>Cuenta</th>
		
		<th style="display:none">
		<select class="cajas" id="selectDepartamentos" style="width:150px" onchange="obtenerGastos()"> 
				<option value="0">Departamento</option>';
			
			foreach($departamentos as $row)
			{
				$seleccionado	=$idDepartamento==$row->idDepartamento?'selected="selected"':'';
				echo '<option '.$seleccionado.' value="'.$row->idDepartamento.'">'.$row->nombre.'</option>';
			}
				
			echo'
			</select>
		</th>
		<th style="display:none">
		<select class="cajas" id="selectGastos" style="width:150px" onchange="obtenerGastos()"> 
				<option value="0">Tipo</option>';
			
			foreach($tipos as $row)
			{
				$seleccionado	=$idGasto==$row->idGasto?'selected="selected"':'';
				echo '<option '.$seleccionado.' value="'.$row->idGasto.'">'.$row->nombre.'</option>';
			}
				
			echo'
			</select>
		</th>
		
		<th>Factura</th>
		<th>Remisión</th>
		
		<th>Subtotal</th>
		<th>Impuesto</th>
		<th>Total</th>
		
		<th>Comprobantes</th>
	</tr>';


if($gastos!=null)
{
	$i=$limite;
	foreach($gastos as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$proveedor	= $this->proveedores->obtenerProveedor($row->idProveedor);
		$ficheros	= $this->administracion->obtenerComprobantesEgresos($row->idEgreso);
		$banco 		= explode('|',$row->banco);
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.($proveedor!=null?$proveedor->empresa:'').'</td>
			<td>'.(strlen($row->productoCatalogo)>1?$row->productoCatalogo:$row->producto).' <img src="'.base_url().'img/egresos.png" width="22" height="22" title="Detalles de gastos" onclick="obtenerGastoInformacion('.$row->idEgreso.')"  /></td>
			
			<td align="center">'.$row->forma.'</td>
			<td align="center">'.$row->cheque.$row->transferencia.'</td>
			
			<td align="center">'.(strlen($row->banco)>3?$banco[1]:'').'</td>
			<td align="center">'.(strlen($row->banco)>3?$banco[0]:'').'</td>
			
			
			<td align="center">'.($row->esRemision=='0'?$row->remision:'').'</td>
			<td align="center">'.($row->esRemision=='1'?$row->remision:'').'</td>
			<td align="right">$'.number_format($row->subTotal,2).'</td>
			<td align="right">$'.number_format($row->ivaTotal,2).'</td>
			<td align="right">$'.number_format($row->pago,2).'</td>
			
			<td>';
			$f=0;
			foreach($ficheros as $fic)
			{
				if(file_exists(carpetaEgresos.$fic->idComprobante.'_'.$fic->nombre) and strlen($fic->nombre)>3)
				{	
					echo $f>0?'<br />':'';
					echo'<a class="dercargas" title="Descargar" href="'.base_url().'produccion/descargarFicheroEgreso/'.$fic->idComprobante.'">'.$fic->nombre.'</a>';
					#echo $fic->xml=='1'?'<br /><i>XML, PDF contabilidad</i>':'';
				}
				else
				{
					echo $f>0?'<br />':'';
					echo '<i>'.$fic->nombre.' (No se encuentra el comprobante)</i>';
					#echo $fic->xml=='1'?'<br /><i>XML, PDF contabilidad</i>':'';
				}
				
				$f++;
			}
			
			echo'
			</td>
		</tr>';
		
		$i++;
	}
}
#else
#{
#	echo '<div class="Error_validar">Sin registro de gastos</div>';
#}

echo '</table>
<div style="width:90%; margin-top:0%;">
	<ul id="pagination-digg" class="ajax-pagGastos">'.$this->pagination->create_links().'</ul>
</div>';
?>