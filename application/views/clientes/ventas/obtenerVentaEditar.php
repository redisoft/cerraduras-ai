<?php
echo '
<script>
$(document).ready(function()
{
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

	$("#txtBuscarProducto").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		milisegundos 	= 500; // milliseconds
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerProductosVenta();
		}, milisegundos);
	});
	
	
	$("#tablaVentas tr:even").addClass("sombreado");
	$("#tablaVentas tr:odd").addClass("sinSombra");  
});

</script>

<input type="hidden" id="txtNumeroProductos" 		name="txtNumeroProductos" value="'.count($productos).'" />
<input type="hidden" id="txtIdCotizacionReutilizar" name="txtIdCotizacionReutilizar" value="'.$cotizacion->idCotizacion.'" />
<input type="hidden" id="txtClaveDescuento" 		name="txtClaveDescuento" value="'.$claveDescuento.'" />

<div style="width:370px; float: left">
	
	<div class="listaVentas">
		<div  id="carritoVacio"></div>
		
		<table class="admintable" width="100%" id="tablaVentas">';
		
		$i=1;
		foreach($productos as $row)
		{
			echo '
			<tr id="filaProducto'.$i.'">
			<td class="filaProducto" width="80%">
			<label>'.$row->descripcion.'</label><br />
			<label class="informacionUnidad" style="margin-left:0px">';
			
			if($claveDescuento!='')
			{
				echo '<img src="'.base_url().'img/descuento.png" onclick="accesoAsignarDescuento('.$i.')" style="cursor:pointer" title="Asignar descuento" />';
			}
				
			echo'
			<input type="text" maxlength="8" id="txtCantidadProducto'.$i.'" name="txtCantidadProducto'.$i.'" class="cajasCantidades" value="'.round($row->cantidad,decimales).'" onchange="calcularFilaProducto('.$i.')" onkeypress="return soloDecimales(event)" /> '.$row->unidad.'
			a $<input type="text" style="width:70px" class="cajas" id="txtPrecioProducto'.$i.'" name="txtPrecioProducto'.$i.'" 	value="'.round($row->precio,decimales).'" placeholder="Precio" onchange="calcularFilaProducto('.$i.')" onkeypress="return soloDecimales(event)" maxlength="15"/></label>
			<br /> <label id="lblDescuento'.$i.'" style="font-size:13px; margin-left:81px">Desc $'.round($row->descuento,decimales).'</label>
			</td>
			
			<td class="filaProducto" id="filaTotal'.$i.'" align="right">$'.round($row->importe,4).'</td>
			<td class="filaProducto">
			<img src="'.base_url().'img/borrar.png" onclick="quitarProductoProducto('.$i.')" />
			</td>
			
			<input type="hidden" id="txtNombreProducto'.$i.'" 	name="txtNombreProducto'.$i.'" 	value="'.$row->descripcion.'" />
			<input type="hidden" id="txtTotalProducto'.$i.'" 	name="txtTotalProducto'.$i.'" 	value="'.$row->importe.'"/>
			<input type="hidden" id="txtIdProducto'.$i.'" 		name="txtIdProducto'.$i.'" 		value="'.$row->idProducto.'"/>

			<input type="hidden" id="txtServicio'.$i.'" 		name="txtServicio'.$i.'" 		value="'.$row->servicio.'" />
			
			<input type="hidden" id="txtDescuentoProducto'.$i.'" value="'.$row->descuento.'" />
			<input type="hidden" id="txtDescuentoPorcentaje'.$i.'" value="'.$row->descuentoPorcentaje.'" />';
			
			$i++;
		}
		
		echo'
		</table>
	</div>
	<table class="admintable" width="100%">
		<tr>
			<!--<td align="right" colspan="3" id="filaSubTotal" class="filaSubTotal">
				SUBTOTAL: $ '.round($cotizacion->subTotal,4).'
			</td>-->
			
			<td align="right" colspan="3" class="filaSubTotal">
				
				'.(strlen($claveDescuento)>0?'<img src="'.base_url().'img/descuento.png" onclick="accesoAsignarDescuento(0)" style="cursor:pointer" title="Asignar descuento" width="20" />':'').'
				
				<label id="filaSubTotal" style="font-size: 20px;">SUBTOTAL:$'.round($cotizacion->subTotal,decimales).'</label>
				
				<br />
				<label id="filaDescuento" style="font-size: 15px;">DESC: $'.round($cotizacion->descuento,decimales).' </label>
				
				<input type="hidden" id="txtDescuentoPorcentaje0" value="'.$cotizacion->descuentoPorcentaje.'" />
				<input type="hidden" id="txtDescuentoProducto0" value="'.$cotizacion->descuento.'" />
				
			</td>
			
		</tr>
		<tr>
			<td colspan="3">
				<input  type="text" class="cajas" id="txtBuscarCliente" style="width:300px"  value="'.$cliente->empresa.'" />
				<input type="hidden" id="txtIdCliente" value="'.$cotizacion->idCliente.'" />
				<input type="hidden" id="txtCreditoDias" value="'.$cotizacion->diasCredito.'" />
				<!--<img src="'.base_url().'img/clientes.png" onclick="formularioClientes(\'venta\')" title="Nuevo cliente" width="22" />-->
			</td>
		</tr>

		<tr>
			<td colspan="1">
				<label>Fecha: </label>
				<input type="text" style="width:120px" class="cajas" id="txtFechaVenta" name="txtFechaVenta" value="'.substr($cotizacion->fecha,0,16).'" />
				<script>
					$("#txtFechaVenta").timepicker()
				</script>
			</td>
			<td colspan="2">
				<textarea id="txtObservacionesVenta" name="txtObservacionesVenta" class="TextArea" style="height:50px; width:200px" placeholder="Observaciones" >'.$cotizacion->observaciones.'</textarea>
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
	
	<input type="text" class="cajas" id="txtBuscarProducto" style="width:250px" placeholder="Buscar productos / servicios"  />
	
	<!--<div class="lineasPuntoVenta" >
		<div class="puntoVenta" onclick="definirLinea(0)">
			<img src="'.base_url().carpetaProductos.'default.png" />
			<section>Todos las líneas</section>
		</div>';
		foreach($lineas as $row)
		{
			echo '
			<div class="puntoVenta" onclick="definirLinea('.$row->idLinea.')">';
			
			if(file_exists(carpetaProductos.$row->imagen) and strlen($row->imagen)>4)
			{
				echo '<img src="'.base_url().carpetaProductos.$row->imagen.'"  align="center" />';
			}
			else
			{
				echo '<img src="'.base_url().carpetaProductos.'default.png" />';
			}
			
			echo'
				<section>'.$row->nombre.'</section>
			</div>';
		}
	echo'
	</div>-->
	
	<div id="obtenerProductosVenta" align="right" class="productosPuntoVenta">
	</div>
</div>';