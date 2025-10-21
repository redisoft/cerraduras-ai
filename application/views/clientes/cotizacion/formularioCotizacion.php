
<script>


$(document).ready(function()
{
	$('#txtBuscarProducto').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerProductosVenta();
		}
	});
	
	$("#txtBuscarCliente").autocomplete(
	{
		source: base_url+"configuracion/obtenerClientes/0",
		
		select:function( event, ui)
		{
			$("#txtIdCliente").val(ui.item.idCliente);
			$("#txtPrecioCliente").val(ui.item.precio);
			
			if(preciosActivo=='1' )
			{
				obtenerProductosVenta();
			}
			
			/*obtenerBancos();
			obtenerProductosVenta()*/
			
			obtenerContactosClienteCotizacion(ui.item.idCliente);
			
			$("#tablaVentas").html("");
			calcularSubTotal();
		}
	});
		
});

</script>

<?php
echo '


<form id="frmCotizaciones" name="frmCotizaciones">
	<input type="hidden" id="txtClaveDescuento" name="txtClaveDescuento" value="'.$claveDescuento.'" />
	<input type="hidden" id="txtNumeroProductos" 	name="txtNumeroProductos" 	value="0" />
	<input type="hidden" id="txtPrecioCliente" 		name="txtPrecioCliente" 	value="'.($cliente!=null?$cliente->precio:1).'" />
	
	<div style="width:370px; float: left">
		
		<div class="listaVentas">
			<div class="Error_validar" id="carritoVacio">Carrito de ventas vacio</div>
			
			<table class="admintable" width="100%" id="tablaVentas">
			</table>
		</div>
		<table class="admintable" width="100%">
			<tr>
				<td align="right" colspan="3"  class="filaSubTotal">
	
					'.(strlen($claveDescuento)>0?'<img src="'.base_url().'img/descuento.png" onclick="accesoAsignarDescuento(0)" style="cursor:pointer; display: none " title="Asignar descuento" width="20" />':'').' 
					
					<label id="filaTotal" style="font-size: 20px;">TOTAL: $0.00 </label>
					
					<br />
					
					<label id="filaDescuento" 	style="font-size: 12px;">DESC: $0.00 </label> |
					
					<label id="filaSubTotal" 	style="font-size: 12px;">SUBTOTAL: $0.00 </label> |
					
					<label id="filaIva" 		style="font-size: 12px;">IMPUESTOS: $0.00 </label> 
					
					<input type="hidden" id="txtDescuentoPorcentaje0" 	name="txtDescuentoPorcentaje0" value="0" />
					<input type="hidden" id="txtDescuentoProducto0" 	name="txtDescuentoProducto0" value="0" />
	
				</td>
			</tr>
			<tr>
				<td colspan="3">
					'.(isset($cliente->empresa)?$cliente->empresa:'').' <input type="hidden" id="txtIdCliente" name="txtIdCliente" value="'.$idCliente.'" />
					
					'.($cliente==null?'<input type="text" style="width:340px" class="cajas" id="txtBuscarCliente" name="txtBuscarCliente" placeholder="Buscar cliente" />':'').'
					
					<div id="obtenerContactosClienteCotizacion">
						<select id="selectContactosClienteCotizacion" name="selectContactosClienteCotizacion" class="cajas" style="width:250px">
							<option value="0">Seleccione contacto</option>
						</select>
					</div>
				
				</td>
			</tr>
	
			<tr>
				<td colspan="1">
					<label>Cotización: </label>
					<input type="text" style="width:120px" class="cajas" id="txtSerie" name="txtSerie" value="'.$serie.'" />
					
				</td>
				<td colspan="2">
					<textarea id="txtComentarios" name="txtComentarios" class="TextArea" style="height:50px; width:200px" placeholder="Comentarios" ></textarea>
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
	</div>
</form>';