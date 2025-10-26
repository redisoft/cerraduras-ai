<script>
$("#txtBuscarCliente").autocomplete(
{
	source:base_url+'configuracion/obtenerClientes',
	
	select:function( event, ui)
	{
		$("#txtIdCliente").val(ui.item.idCliente);
		obtenerBancos();
		/*obtenerProductosVenta();
		$("#tablaVentas").html("<tbody></tbody>");
		calcularSubTotal();*/
	}
});

$(document).ready(function()
{
	$("#txtBuscarProducto").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerProductosVenta();
		}, 700);
	});
});

$("#txtBuscarCodigo").keypress(function(e)
 {
	if(e.which == 13) 
	{
		e.preventDefault();
		obtenerProductoCodigo();
		
	}
});


</script>


<?php
echo '



<form id="frmVentasClientes" name="frmVentasClientes" action="javascript:formularioCobros()">
<div class="col-md-4">

		<input type="hidden" id="txtClaveDescuento" 	name="txtClaveDescuento" 	value="'.$claveDescuento.'" />
		<input type="hidden" id="txtNumeroProductos" 	name="txtNumeroProductos" 	value="0" />
		<input type="hidden" id="txtTipoUsuarioActivo" 	name="txtTipoUsuarioActivo" 	value="'.tipoUsuario.'" />
		
		<input type="hidden" id="txtVentasF4" 			name="txtVentasF4" 			value="'.$ventasF4.'" />
		<input type="hidden" id="txtLimiteVentas" 		name="txtLimiteVentas" 			value="'.$limiteVentas.'" />
		<input type="hidden" id="txtTotalPrevio" 	 	value="0" />
		
		<div class="listaVentas">
			<div class="Error_validar" id="carritoVacio">Carrito de ventas vacio</div>
			
			<table class="admintable" width="100%" id="tablaVentas">
				<tbody>
				</tbody>
			</table>
		</div>
	
	<table class="admintable" width="100%">
		<tr>
			<td align="right" colspan="3" class="filaSubTotal">
			
				
				'.(strlen($claveDescuento)>0?'<img src="'.base_url().'img/descuento.png" onclick="accesoAsignarDescuento(0)" style="cursor:pointer; " title="Asignar descuento" width="20" />':'').' 
				
				
				
				<label id="filaTotal" style="font-size: 2vh;">TOTAL: $0.00 </label>
				
				<br />
				
				
				<label id="filaSubTotal" 	style="font-size: 1.2vh;">SUBTOTAL: $0.00 </label> |
				
				<label id="filaDescuento" 	style="font-size: 1.2vh;">DESC: $0.00 </label> |
				
				<label id="filaIva" 		style="font-size: 1.2vh;">IMPUESTOS: $0.00 </label> 
				
				<input type="hidden" id="txtDescuentoPorcentaje0" value="0" />
				<input type="hidden" id="txtDescuentoProducto0" value="0" />
				
			</td>
		</tr>
		<tr>
			<td colspan="3">';
				
				if(sistemaActivo=='olyess')
				{
					echo'
					<input placeholder="'.($cliente!=null?$cliente->empresa:'Público en general').'" type="text" class="cajas" id="txtBuscarCliente" style="width:50vh; height: 2.5vh; font-size: 1.5vh"  />
					<input type="hidden" id="txtIdCliente" 	name="txtIdCliente" value="'.($cliente!=null?$cliente->idCliente:1).'" />';
				}
				else
				{
					echo'<input placeholder="'.($cliente!=null?$cliente->empresa:'Público en general').'" type="text" class="cajas" id="txtBuscarCliente" style="width:50vh; height: 2.5vh; font-size: 1.5vh"  />
					<input type="hidden" id="txtIdCliente" 	name="txtIdCliente" value="'.($cliente!=null?$cliente->idCliente:1).'" />';
				}
				
				
				echo'
				
				<input type="hidden" id="txtCreditoDias" name="txtCreditoDias" value="0" />
				<img src="'.base_url().'img/clientes.png" onclick="formularioClientes(\'venta\')" title="Nuevo cliente" style="width:2.4vh" />
			</td>
		</tr>

		<tr>
			<td colspan="1">
				<label>Fecha: </label>
				<input type="text" style="width:15.5vh; font-size: 1.5vh; height: 2.5vh;" class="cajas" id="txtFechaVenta" name="txtFechaVenta" value="'.date('Y-m-d H:i').'" />
				<script>
					$("#txtFechaVenta").timepicker()
				</script>
			</td>
			<td colspan="2">
				<textarea id="txtObservacionesVenta" name="txtObservacionesVenta" class="TextArea" style="height:7vh; width:35vh" placeholder="Observaciones" ></textarea>
			</td>
		</tr>
		
	</table>
</div>

<div class="col-md-8">

	<table>
		<tr>
			<td>
				<input type="hidden" id="txtIdLinea" name="txtIdLinea" value="0" />
	
				<select class="cajas" id="selectLineas" name="selectLineas" style="width:20vh; height: 2.5vh; font-size: 1.5vh" onchange="obtenerSubLineasVentas(this.value);">
					<option value="0">Seleccione línea</option>';
					
					foreach($lineas as $row)
					{
						echo '<option value="'.$row->idLinea.'">'.$row->nombre.'</option>';
					}
				
				echo'
				</select>
			</td>
			<td id="obtenerSubLineas">
				<select class="cajas" id="selectSubLineas" name="selectSubLineas" '.(sistemaActivo=='pinata'?'style="display:none"':'style="width:20vh; height: 2.5vh; font-size: 1.5vh"').'  onchange="obtenerProductosVenta()">
				<option value="0">Seleccione sublinea</option>';
				
				foreach($sublineas as $row)
				{
					echo '<option value="'.$row->idSubLinea.'">'.$row->nombre.'</option>';
				}
			
			echo'
			</select>
			</td>
			<td>
				<input type="text" class="cajas" id="txtBuscarProducto" style="width:25vh; height: 2.5vh; font-size: 1.5vh" placeholder="Buscar productos / servicios"  />
			</td>
			<td>
				<input type="text" class="cajas" id="txtBuscarCodigo" style="width:25vh; height: 2.5vh; font-size: 1.5vh" placeholder="Buscar por código de barras"  />
			</td>
		</tr>
	</table>
	
	
	
	<div id="obtenerProductosVenta" class="productosPuntoVenta">';
	
	foreach($lineas as $row)
	{
		echo '<div class="puntoVentaLineas" onclick="obtenerSubLineasVentas('.$row->idLinea.')">';
		
		if(file_exists(carpetaProductos.$row->imagen) and strlen($row->imagen)>4)
		{
			echo '<img src="'.base_url().carpetaProductos.$row->imagen.'" />';
		}
		else
		{
			echo '<img src="'.base_url().carpetaProductos.'default.png" />';
		}
		
		echo '<section>'.$row->nombre.'</section>
		
		</div>';
	}
		
	echo'
	</div>
	
	<div align="right" style="margin-top:5px">
	
		<input type="button" value="Transferencias(F6)" class="botonPuntoVenta"  style="margin-right:1vh; width: 13vh"  onclick="obtenerTraspasos()">
		<input type="button" value="Regresar(F4)" class="botonPuntoVenta"  style="margin-right:1vh" onclick="obtenerSubLineasVentas(0)">';
		
		if(sistemaActivo=='olyess')
		{
			echo'<input type="button" value="Pedidos" class="botonPuntoVenta"  style="margin-right:1vh" id="btnCobros" onclick="formularioPedidos()" >';
		}
		
		echo'
		<input type="button" value="Cancelar(F2)" class="botonPuntoVenta"  style="margin-right:1vh" id="btnCancelarVenta" onclick="formularioVentas()">
		<input type="button" value="Cobrar(F3)" class="botonPuntoVenta"  style="margin-right:7vh" id="btnCobros" onclick="formularioCobros()" >
		
	</div>
</div>
</form>';
