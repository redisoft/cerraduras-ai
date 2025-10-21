<?php
$idCliente		= $this->input->post('idCliente');
$precio			= $this->clientes->obtenerPrecioCliente($idCliente);
$precioCliente	= $precio!=null?$precio->precio:'';
		
if($productos!=null)
{
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagVen">'.$this->pagination->create_links().'</ul>
	</div>
	<table class="admintable" width="100%">
		<tr>
			<th width="12%">Código</th>
			<th width="20%">Nombre</th>
			<th width="20%">Descripción</th>
			<th>Unidad</th>
			<th>Stock</th>
			<th>Sucursales</th>
			<th>Precio</th>
		</tr>';
	
	$i=1;
	foreach($productos as $row)
	{
		$idPeriodo			= $row->servicio==1?$row->idPeriodo:0;
		$onclick			= 'onclick="agregarProductoVenta('.$i.','.$row->servicio.',\'si\')"';
		$stock				= $row->stock;
		$stockSucursales	= $this->inventarioProductos->obtenerStockSucursales($row->idProducto);

		$precioA	= $row->precioA;
		$precioB	= $row->precioB;
		$precioC	= $row->precioC;
		$precioD	= $row->precioD;
		$precioE	= $row->precioE;
		
		$impuestoA	= $precioA - $row->precioA / (1+($row->tasa/100));
		$impuestoB	= $precioB - $row->precioB / (1+($row->tasa/100));
		$impuestoC	= $precioC - $row->precioC / (1+($row->tasa/100));
		$impuestoD	= $precioD - $row->precioD / (1+($row->tasa/100));
		$impuestoE	= $precioE - $row->precioE / (1+($row->tasa/100));

		$precio	= $row->precioImpuestos;
		
		
		
		
		echo"<input type='hidden' id='txtNombre".$i."' value='".$row->nombre."' />";

		echo'		
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td '.$onclick.'>';
			
			/*if(strlen($row->codigoBarras)>1)
			{
				echo'
				<div id="codigoBarras'.$i.'" ></div>
				<script>
					$("#codigoBarras'.$i.'").barcode("'.$row->codigoBarras.'", "code93",{barWidth:1, barHeight:40})
				</script>';

			}*/
			
			echo $row->codigoInterno;
			
			echo'
			</td>
			<td  '.$onclick.'>'.$row->nombre.'</td>
			<td  '.$onclick.'>'.$row->descripcion.'</td>
			<td '.$onclick.'>'.$row->unidad.'</td>
			<td align="center">'.number_format($stock,decimales).'</td>
			<td align="center" onclick="obtenerStockSucursales('.$row->idProducto.')">'.number_format($stockSucursales,decimales).'</td>
			<td align="center">
				<select id="selectPrecios'.$i.'" class="cajasPrecios" style="height: 23px; width:100px" >
			
				<option '.($precioCliente==1?'selected="selected"':'').' value="'.($precioA).'">$'.number_format($precioA,decimales).'</option>
				<option '.($precioCliente==2?'selected="selected"':'').' value="'.($precioB).'">$'.number_format($precioB,decimales).'</option>
				<option '.($precioCliente==3?'selected="selected"':'').' value="'.($precioC).'">$'.number_format($precioC,decimales).'</option>
				<option '.($precioCliente==4?'selected="selected"':'').' value="'.($precioD).'">$'.number_format($precioD,decimales).'</option>
				<option '.($precioCliente==5?'selected="selected"':'').' value="'.($precioE).'">$'.number_format($precioE,decimales).'</option>
				
			</select>
			</td>
		</tr>
		
		<input type="hidden" id="txtActualPrecio'.$i.'" 	name="txtActualPrecio'.$i.'" value="'.$precioA.'"/>
		
		<input type="hidden" id="txtCodigoProducto'.$i.'" 	value="'.$row->codigoInterno.'" />
		<input type="hidden" id="txtCantidadTotal'.$i.'" 	value="'.($row->servicio==0?$stock:100000).'" />
		<input type="hidden" id="txtIDProducto'.$i.'" 		value="'.$row->idProducto.'" />
		<input type="hidden" id="idPeriodo'.$i.'" 			name="idPeriodo'.$i.'" value="'.$idPeriodo.'"/>
		<input type="hidden" id="txtPeriodo'.$i.'" 			name="txtPeriodo'.$i.'" value="'.$row->periodo.'"/>
		<input type="hidden" id="txtUnidad'.$i.'" 			name="txtUnidad'.$i.'" value="'.$row->unidad.'"/>
		
		<input type="hidden" id="txtImpuestoNombre'.$i.'" 	name="txtImpuestoNombre'.$i.'" 		value="'.$row->impuesto.'"/>
		<input type="hidden" id="txtImpuestoTasa'.$i.'" 	name="txtImpuestoTasa'.$i.'" 		value="'.$row->tasa.'"/>
		<input type="hidden" id="txtImpuestoTipo'.$i.'" 	name="txtImpuestoTipo'.$i.'" 		value="'.$row->tipoImpuesto.'"/>
		<input type="hidden" id="txtImpuestoTotal'.$i.'" 	name="txtImpuestoTotal'.$i.'" 		value="'.$impuestoA.'"/>
		<input type="hidden" id="txtImpuestoId'.$i.'" 		name="txtImpuestoId'.$i.'" 			value="'.$row->idImpuesto.'"/>
		
		'.(sistemaActivo=='olyess'?'
		<input type="hidden" id="txtDomicilio'.$i.'" 		name="txtDomicilio'.$i.'" 			value="'.$row->domicilio.'"/>
		<input type="hidden" id="txtIdLinea'.$i.'" 			name="txtIdLinea'.$i.'" 			value="'.$row->idLinea.'"/>':'').'';
		
		
		
		$i++;
	}
	
	#echo'</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de productos</div>';
}