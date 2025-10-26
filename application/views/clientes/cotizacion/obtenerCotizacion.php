<?php
echo '
<script>
$("#txtBuscarCliente").autocomplete(
{
	source:"'.base_url().'configuracion/obtenerClientes",
	
	select:function( event, ui)
	{
		$("#txtIdCliente").val(ui.item.idCliente);
		obtenerBancos();
		obtenerProductosVenta()
	}
});

$("#tablaVentas tr:even").addClass("sombreado");
$("#tablaVentas tr:odd").addClass("sinSombra");  
	
</script>

<form id="frmCotizaciones" name="frmCotizaciones">
	<input type="hidden" id="txtClaveDescuento" name="txtClaveDescuento" value="'.$claveDescuento.'" />
	<input type="hidden" id="txtNumeroProductos" 	name="txtNumeroProductos" 	value="'.count($productos).'" />
	
	<div style="width:370px; float: left">
		
		<div class="listaVentas">
			<div id="carritoVacio" ></div>
			
			<table class="admintable" width="100%" id="tablaVentas">';
			
			$i	= 1;
			foreach($productos as $row)
			{
				$producto	= strlen($row->nombre)>0?$row->nombre:$row->producto;
				$impuestos	= $this->clientes->obtenerImpuestosCotizacion($row->idProducto);
				
				$impuesto	= $row->impuestos/$row->cantidad;
				
				echo '
				<tr id="filaProducto'.$i.'">
				<td class="filaProducto" width="80%">
				<label>'.$producto.'</label><br />
				<label class="informacionUnidad" style="margin-left:0px">';
					
					if($claveDescuento!='')
					{
						echo '<img src="'.base_url().'img/descuento.png" onclick="accesoAsignarDescuento('.$i.')" style="cursor:pointer; " title="Asignar descuento" />';
					}
					
					echo'
					<input type="text" maxlength="8" id="txtCantidadProducto'.$i.'" name="txtCantidadProducto'.$i.'" class="cajasCantidades" value="'.round($row->cantidad,2).'" onchange="calcularFilaProducto('.$i.')" onkeypress="return soloDecimales(event)" />'.$row->unidad.'
					a <input type="text" style="width:80px" class="cajas" id="txtPrecioProducto'.$i.'" name="txtPrecioProducto'.$i.'" 	value="'.round($row->precio+$impuesto,decimales).'" placeholder="Precio" onchange="calcularFilaProducto('.$i.')" onkeypress="return soloDecimales(event)" maxlength="15"/>
					
					</label>
					<br /> <label id="lblDescuento'.$i.'" style="font-size:13px; margin-left:81px">Desc $'.round($row->descuento,decimales).'</label>
					
				</td>
				<td class="filaProducto" id="filaTotal'.$i.'" align="right">$'.number_format($row->importe,2).'</td>
				<td class="filaProducto">
					<img src="'.base_url().'img/borrar.png" onclick="quitarProductoProducto('.$i.')" />
				</td>
				
					<input type="hidden" id="txtNombreProducto'.$i.'" 		name="txtNombreProducto'.$i.'" 	value="'.$producto.'" />
					<input type="hidden" id="txtTotalProducto'.$i.'" 		name="txtTotalProducto'.$i.'" 	value="'.($row->importe+$row->impuestos).'" />
					<input type="hidden" id="txtIdProducto'.$i.'" 			name="txtIdProducto'.$i.'" 		value="'.$row->idProduct.'" />
					
					<input type="hidden" id="txtServicio'.$i.'" 			name="txtServicio'.$i.'" 		value="'.$row->servicio.'" />
					
					<input type="hidden" id="txtDescuentoProducto'.$i.'"	name="txtDescuentoProducto'.$i.'"	 value="'.$row->descuento.'" />
					<input type="hidden" id="txtDescuentoPorcentaje'.$i.'" 	name="txtDescuentoPorcentaje'.$i.'"	value="'.$row->descuentoPorcentaje.'" class="descuentosProductos"/>';
					
					if($impuestos!=null)
					{
						foreach($impuestos as $imp)
						{
							echo'
							<input type="hidden" id="txtImpuesto'.$i.'" 			name="txtImpuesto'.$i.'"		value="'.$imp->nombre.'" />
							<input type="hidden" id="txtTasaImpuesto'.$i.'" 		name="txtTasaImpuesto'.$i.'"	value="'.$imp->tasa.'" />
							<input type="hidden" id="txtTipoImpuesto'.$i.'" 		name="txtTipoImpuesto'.$i.'"	value="'.$imp->tipo.'" />
							<input type="hidden" id="txtIdImpuesto'.$i.'" 			name="txtIdImpuesto'.$i.'"		value="'.$imp->idImpuesto.'" />
							<input type="hidden" id="txtTotalImpuesto'.$i.'" 		name="txtTotalImpuesto'.$i.'"	value="'.$imp->importe.'" />';
						}
					}
					else
					{
						echo'
						<input type="hidden" id="txtImpuesto'.$i.'" 			name="txtImpuesto'.$i.'"		value="" />
						<input type="hidden" id="txtTasaImpuesto'.$i.'" 		name="txtTasaImpuesto'.$i.'"	value="0" />
						<input type="hidden" id="txtTipoImpuesto'.$i.'" 		name="txtTipoImpuesto'.$i.'"	value="IVA" />
						<input type="hidden" id="txtIdImpuesto'.$i.'" 			name="txtIdImpuesto'.$i.'"		value="0" />
						<input type="hidden" id="txtTotalImpuesto'.$i.'" 		name="txtTotalImpuesto'.$i.'"	value="0" />';
					}
					
				
				echo'
				</tr>';
				
				$i++;
			}
			echo'
			</table>
		</div>
		<table class="admintable" width="100%">
			<tr>
				<td align="right" colspan="3" class="filaSubTotal">
					
					'.(strlen($claveDescuento)>0?'<img src="'.base_url().'img/descuento.png" onclick="accesoAsignarDescuento(0)" style="cursor:pointer;" title="Asignar descuento" width="20" />':'').'
					
					<label id="filaTotal" style="font-size: 20px;">TOTAL: $0.00 </label>
					
					
					
					<br />
					<label id="filaDescuento" style="font-size: 12px;">DESC: $'.$cotizacion->descuento.' </label>|
					
					<label id="filaSubTotal" 	style="font-size: 12px;">SUBTOTAL: $0.00 </label> |
					
					<label id="filaIva" 		style="font-size: 12px;">IMPUESTOS: $0.00 </label> 
					
					<input type="hidden" id="txtDescuentoPorcentaje0" 	name="txtDescuentoPorcentaje0" value="'.$cotizacion->descuentoPorcentaje.'" />
					<input type="hidden" id="txtDescuentoProducto0"  	name="txtDescuentoProducto0" value="'.$cotizacion->descuento.'" />
					
				</td>
			</tr>
			<tr>
				<td colspan="3">
					'.$cotizacion->empresa.'
					<br />
					<select id="selectContactosClienteCotizacion" name="selectContactosClienteCotizacion" class="cajas" style="width:250px">
						<option value="0">Seleccione contacto</option>';
	
						foreach($contactos as $row)
						{
							echo '<option '.($row->idContacto==$cotizacion->idContacto?'selected="selected"':'').' value="'.$row->idContacto.'">'.$row->nombre.'</option>';
						}
						
					echo'
					</select>
					
					
					<input type="hidden" id="txtIdCliente" 			name="txtIdCliente" value="'.$cotizacion->idCliente.'" />
					<input type="hidden" id="txtIndiceCotizacion" 	name="txtIndiceCotizacion"  value="'.count($productos).'" />
					<input type="hidden" id="txtIdCotizacion"  		name="txtIdCotizacion"value="'.$cotizacion->idCotizacion.'" />
				</td>
			</tr>
	
			<tr>
				<td colspan="1">
					<label>Cotización: </label>
					<input type="text" style="width:120px" class="cajas" id="txtSerie" name="txtSerie" value="'.$cotizacion->serie.'" />
					
				</td>
				<td colspan="2">
					<textarea id="txtComentarios" name="txtComentarios" class="TextArea" style="height:50px; width:200px" placeholder="Comentarios" >'.$cotizacion->comentarios.'</textarea>
				</td>
			</tr>
			
		</table>
	</div>
	
	<div style="width:500px; float: right">
		
		<input type="hidden" id="txtIdLinea" name="txtIdLinea" value="0" />
		
		<select class="cajas" id="selectLineas" name="selectLineas" style="width:170px" onchange="obtenerProductosVenta()">
			<option value="0">Seleccione</option>';
			
			foreach($lineas as $row)
			{
				echo '<option value="'.$row->idLinea.'">'.$row->nombre.'</option>';
			}
		
		echo'
		</select>
		
		<input type="text" class="cajas" id="txtBuscarProducto" onkeyup="obtenerProductosVenta()" style="width:250px" placeholder="Buscar productos / servicios"  />
	<div id="obtenerProductosVenta" class="productosPuntoVenta">
		</div>
	</div>
</form>';
