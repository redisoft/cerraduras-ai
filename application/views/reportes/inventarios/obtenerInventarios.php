<?php
#if($inventarios!=null)
#{

	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagInventarios">'.$this->pagination->create_links().'</ul>
	</div>';
	
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="5" class="encabezadoPrincipal" align="right" style="border-right:none"> 
				Inventario productos
			</th>
			<th  class="encabezadoPrincipal" style="border-right:none; border-left:none" colspan="2">
				<img id="btnExportarPdfReporte" onclick="reporteInventarios(\''.$idProducto.'\','.$idLinea.')" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
				
				&nbsp;&nbsp;
				<img id="btnExportarExcelReporte" onclick="excelInventarios(\''.$idProducto.'\','.$idLinea.')" src="'.base_url().'img/excel.png" width="22" title="Excel" />
				<br />
				<a>PDF</a>
    			<a>Excel</a> ';
			
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
			<th colspan="4" class="encabezadoPrincipal" style="border-left:none" align="right">Total: $'.number_format($total,2).'</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Código</th>
			<th>Artículo</th>
			<th>
			Línea
			<select class="cajas" id="selectLineas" style="width:100px; display: none" onchange="obtenerInventarios()">
				<option value="0">Línea</option>';
				
				for($i=0;$i<count($arreglos['linea']);$i++)
				{
					if(strlen($arreglos['linea'][$i])>0)
					{
						$seleccionado	=$arreglos['idLinea'][$i]==$idLinea?'selected="selected"':'';
						echo '<option '.$seleccionado.' value="'.$arreglos['idLinea'][$i].'">'.$arreglos['linea'][$i].'</option>';
					}
				}
				
				echo'
				</select>
			</th>
			<th>
			<select class="cajas" id="selectUnidades" style="width:100px" onchange="obtenerInventarios()">
				<option value="0">Unidad</option>';
				
				foreach($unidades as $row)
				{
					echo '<option '.($row->idUnidad==$idUnidad?'selected="selected"':'').' value="'.$row->idUnidad.'">'.$row->nombre.'</option>';
				}
				
				echo'
				</select>
			</th>
			<th>Existencia</th>
			<th>Costo unitario</th>
			<th>Valor total</th>
			<th>Precio 1</th>
			<th>Precio venta</th>
			<th>Precio mayoreo</th>
		</tr>';
	
	$i=$limite+1;
	foreach($inventarios as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.' onclick="obtenerInformacionCompras('.$row->idProducto.')">
			<td align="right">'.$i.'</td>
			<td align="center">'.$row->codigoInterno.'</td>
			<td align="left">'.$row->producto.'</td>
			<td align="center">'.$row->linea.'</td>
			<td align="center">'.$row->unidad.'</td>
			<td align="center">'.$row->stock.'</td>
			<td align="right">$'.number_format($row->precioA,2).'</td>
			<td align="right">$'.number_format($row->precioA*$row->stock,2).'</td>
			<td align="right">$'.number_format($row->precioC,2).'</td>
			<td align="right">$'.number_format($row->precioA,2).'</td>
			<td align="right">$'.number_format($row->precioB,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
	
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagInventarios">'.$this->pagination->create_links().'</ul>
	</div>';
/*}
else
{
	echo '<div class="Error_validar">Sin registro de inventario</div>';
}*/
