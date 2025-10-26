<?php
$idCliente		= $this->input->post('idCliente');
$precio			= $this->clientes->obtenerPrecioCliente($idCliente);
$precio			= $precio!=null?$precio->precio:'';
		
if($productos!=null)
{
	/*echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagVen">'.$this->pagination->create_links().'</ul>
	</div>
	<table class="admintable" width="100%">
		<tr>
			<th width="3%">#</th>
			<th>Código</th>
			<th width="15%">Tipo</th>
			<th width="30%">Nombre</th>
			<th width="90px">Precio</th>
			<th>Stock</th>
			<th width="10%">Acciones</th>
		</tr>';*/
	
	$i=1;
	foreach($productos as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$idPeriodo	= $row->servicio==1?$row->idPeriodo:0;
		$onclick	= 'onclick="agregarProductoVenta('.$i.','.$row->servicio.',\'si\')"';
		$stock		= $row->stock;
		
		//SOLO SI ES LA PIÑATA
		if(sistemaActivo=='pinata')
		{
			if($row->idProducto==1559)
			{
				$stock		= 1000000000;
			}
		}
		
	echo '
		<div class="puntoVenta" title="Agregar '.$row->nombre.'" >';
			
			if(file_exists(carpetaProductos.$row->idProducto.'_'.$row->imagen) and strlen($row->imagen)>4)
			{
				echo '<img src="'.base_url().carpetaProductos.$row->idProducto.'_'.$row->imagen.'"  align="center" '.$onclick.'/>';
			}
			else
			{
				echo '<img src="'.base_url().carpetaProductos.'default.png" '.$onclick.'/>';
			}
			
			echo '<br /><label onclick="obtenerStockSucursales('.$row->idProducto.')">Sucursales</label>';
			
			
			$precioA	= $row->precioA;
			$precioB	= $row->precioB;
			$precioC	= $row->precioC;
			$precioD	= $row->precioD;
			$precioE	= $row->precioE;
			
			$impuestoA	= $row->precioA*($row->tasa/100);
			$impuestoB	= $row->precioB*($row->tasa/100);
			$impuestoC	= $row->precioC*($row->tasa/100);
			$impuestoD	= $row->precioD*($row->tasa/100);
			$impuestoE	= $row->precioE*($row->tasa/100);

			$precio	= $row->precioImpuestos;
			
			echo 
			'<section class="precio" >
				$'.number_format($precio,decimales).'
				<input type="hidden" id="selectPrecios'.$i.'" value="'.($precio).'"/>
				
				<select id="selectPreciosaa'.$i.'" class="cajasPrecios"  style="display:none">';
				
					$seleccionado=$precio==1?'selected="selected"':'';
					echo'<option '.$seleccionado.' value="'.($precioA+$impuestoA).'">$'.number_format($precioA+$impuestoA,2).'</option>';
				echo'
				</select>
			</section>
			
			<section '.$onclick.'>'.$row->nombre.'</section>';
			
			echo"<input type='hidden' id='txtNombre".$i."' value='".$row->nombre."' />";
				
			echo'
			
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
			<input type="hidden" id="txtIdLinea'.$i.'" 			name="txtIdLinea'.$i.'" 			value="'.$row->idLinea.'"/>':'').'
			
		</div>';
		
		
		/*echo '
		<tr '.$estilo.'>
			<td '.$onclick.' align="right">'.$i.'</td>
			<td '.$onclick.' align="center">'.$row->codigoInterno.'</td>
			<td '.$onclick.'>';
				echo $row->servicio==0?'Producto':'Servicio ('.$row->periodo.')';
			echo'</td>
			<td '.$onclick.'>'.$row->nombre.'</td>
			<td align="center">
				<select class="cajas" id="selectPrecios'.$i.'" style="width:90px" >';
					$seleccionado=$precio==1?'selected="selected"':'';
					echo'<option '.$seleccionado.' value="'.$row->precioA.'">'.number_format($row->precioA,2).'</option>';
					$seleccionado=$precio==2?'selected="selected"':'';
					echo'<option '.$seleccionado.' value="'.$row->precioB.'">'.number_format($row->precioB,2).'</option>';
					$seleccionado=$precio==3?'selected="selected"':'';
					echo'<option '.$seleccionado.' value="'.$row->precioC.'">'.number_format($row->precioC,2).'</option>';
					$seleccionado=$precio==4?'selected="selected"':'';
					echo'<option '.$seleccionado.' value="'.$row->precioD.'">'.number_format($row->precioD,2).'</option>';
					$seleccionado=$precio==5?'selected="selected"':'';
					echo'<option '.$seleccionado.' value="'.$row->precioE.'">'.number_format($row->precioE,2).'</option>';
			echo'
				</select>
			</td>
			<td '.$onclick.' align="center">'.$row->stock.'</td>
			<td align="center">
				<input type="text" class="cajas" id="txtCantidad'.$i.'" style="width:60px" 
					onchange="agregarProductoVenta('.$i.','.$row->servicio.',\'no\')" />';
				
				echo"
				<input type='hidden' id='txtNombre".$i."' value='".$row->nombre."' />";
				
				#<input type="hidden" id="txtNombre'.$i.'" value="'.$row->nombre.'" />
				echo'
				<input type="hidden" id="txtCodigoProducto'.$i.'" value="'.$row->codigoInterno.'" />
				<input type="hidden" id="txtCantidadTotal'.$i.'" value="'.$row->stock.'" />
				<input type="hidden" id="txtIdProducto'.$i.'" value="'.$row->idProducto.'" />
				
				<input type="hidden" id="idPeriodo'.$i.'" name="idPeriodo'.$i.'" value="'.$idPeriodo.'"/>
				<input type="hidden" id="txtPeriodo'.$i.'" name="txtPeriodo'.$i.'" value="'.$row->periodo.'"/>

			</td>
		</tr>';	*/
		
		$i++;
	}
	
	#echo'</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de productos</div>';
}
