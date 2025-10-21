<script>

$("#txtBuscarProductoCodigo").focus();
	
$("#txtBuscarCliente").autocomplete(
{
	source:base_url+'configuracion/obtenerClientes',
	
	select:function( event, ui)
	{
		$("#txtIdCliente").val(ui.item.idCliente);
		$("#txtPrecioCliente").val(ui.item.precio);

		$("#txtIdSucursal").val(ui.item.idSucursal!=null?ui.item.idSucursal:0);
		
		//obtenerBancos();
		
		if(preciosActivo=='1' )
		{
			obtenerProductosVenta();
		}
		
		/*obtenerProductosVenta();
		$("#tablaVentas").html("<tbody></tbody>");
		calcularSubTotal();*/
	}
});

$(document).ready(function()
{
	
	$(document).on('click', '#txtBuscarProducto', function(event) 
	{ 
		/*event.preventDefault(); 
		$("#tab1").click(); 
		$("#tab1").trigger('click');*/
		//perrita();
	});
	
	$('#txtBuscarProductoCodigo').keypress(function (e) 
	{
		/*var regex = new RegExp("^[a-zA-Z0-9]+$");
		var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
		if (regex.test(str)) {
			return true;
		}
	
		e.preventDefault();
		return false;*/
	});
	

	$("#txtBuscarProducto,#txtBuscarProductoCodigo,#txtBuscarProveedor").keyup(function(e) 
	{
		if(e.which == 09) 
		{
			return;
		}
		
		/*clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerProductosVenta();
		}, 700);*/
	});
	
	$('#txtBuscarProducto,#txtBuscarProductoCodigo').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerProductosVenta();
		}
	});
	
	/*$('#txtBuscarProductoCodigo').keypress(function(e)
	 {
		if(e.which == 13) 
		{
			obtenerProductosVenta();
			
			if(obtenerNumeros($('#txtNumeroTotalProductos').val())==1)
			{
				agregarProductoVenta(1,0,'si');
				$('#txtBuscarProductoCodigo').val('')
			}
		}
	});*/
	
	$("#txtBuscarUsuarioPunto").autocomplete(
	{
		source:base_url+"configuracion/autoCompletadoUsuarios",
		autoFocus:true,
		select: function(event,ui)
		{
			$("#txtIdUsuarioVendedorPunto").val(ui.item.idUsuario);
		}
	});
});

</script>


<?php
echo '


<!--<div style="width:370px; float: left">-->
<!--<div style="width:420px; float: left">-->
<form id="frmVentasClientes" name="frmVentasClientes" action="javascript:formularioCobros()">



<div class="col-md-4">

		<input type="hidden" id="txtClaveDescuento" 	name="txtClaveDescuento" 	value="'.$claveDescuento.'" />
		<input type="hidden" id="txtNumeroProductos" 	name="txtNumeroProductos" 	value="0" />
		<input type="hidden" id="txtTipoUsuarioActivo" 	name="txtTipoUsuarioActivo" value="'.tipoUsuario.'" />
		
		<input type="hidden" id="txtVentasF4" 			name="txtVentasF4" 			value="'.$ventasF4.'" />
		<input type="hidden" id="txtLimiteVentas" 		name="txtLimiteVentas" 		value="'.$limiteVentas.'" />
		<input type="hidden" id="txtSerie" 				name="txtSerie" 			value="'.$serie.'" />
		
		
		<input type="hidden" id="selectContactosClienteCotizacion" 				name="selectContactosClienteCotizacion" 			value="0" />
		<input type="hidden" id="txtComentarios" 				name="txtComentarios" 			value="" />
		
		<input type="hidden" id="txtPrecioCliente" 		name="txtPrecioCliente" 	value="'.($cliente!=null?$cliente->precio:1).'" />
		
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
			
				
				'.(strlen($claveDescuento)>0?'<img src="'.base_url().'img/descuento.png" onclick="accesoAsignarDescuento(0)" style="cursor:pointer; display: none " title="Asignar descuento" width="20" />':'').' 
				
				
				
				<label id="filaTotal" style="font-size: 2vh;">TOTAL: $0.00 </label>
				
				<br />
				
				
				<label id="filaSubTotal" 	style="font-size: 1.5vh;">SUBTOTAL: $0.00 </label> |
				
				<label id="filaDescuento" 	style="font-size: 1.5vh;">DESC: $0.00 </label> |
				
				<label id="filaIva" 		style="font-size: 1.5vh;">IMPUESTOS: $0.00 </label> 
				
				<input type="hidden" id="txtDescuentoPorcentaje0" value="0" />
				<input type="hidden" id="txtDescuentoProducto0" value="0" />
				
			</td>
		</tr>
		

		<tr style="display: none">
			<td colspan="1">
				<label>Fecha: </label>
				<input type="text" style="width:13vh; height: 2.5vh; font-size: 1.3vh" class="cajas" id="txtFechaVenta" name="txtFechaVenta" value="'.date('Y-m-d H:i').'" />
				<script>
					$("#txtFechaVenta").timepicker()
				</script>
			</td>
			<td colspan="2">
				<textarea id="txtObservacionesVenta" name="txtObservacionesVenta" class="TextArea" style="height:6vh; width:35vh; font-size: 1.3vh" placeholder="Observaciones" ></textarea>
			</td>
		</tr>
		
	</table>
</div>

<div class="col-md-8">

	<table>
		<tr>
			<td colspan="4" style="font-size: 22px">Presione Enter para buscar</td>
		</tr>
		<tr>
			<td>
				
				<input type="text" class="cajas" id="txtBuscarProductoCodigo" style="width:30vh; height: 4.2vh; font-size: 2.5vh" placeholder="Código"  tabindex="1"  />
			</td>
			<td>
				<input type="text" class="cajas" id="txtBuscarProducto" style="width:30vh; height: 4.2vh; font-size: 2.5vh" placeholder="Nombre"  tabindex="2" />
			</td>
			
			<td>
				<input placeholder="'.($cliente!=null?$cliente->empresa:'VENTAS AL PÚBLICO GENERAL').'" type="text" class="cajas" id="txtBuscarCliente" style="width:30vh; height: 4.2vh; font-size: 2.5vh"  tabindex="-1" />
				<input type="hidden" id="txtIdCliente" 	name="txtIdCliente" value="'.($cliente!=null?$cliente->idCliente:1).'" />
				
				<input type="hidden" id="txtIdSucursal" 		name="txtIdSucursal" 		value="0" />
				<input type="hidden" id="txtIdLicenciaActual" 	name="txtIdLicenciaActual" 	value="'.$idLicencia.'" />
				
				
			</td>
			
			<td>
				<input type="hidden" id="txtCreditoDias" name="txtCreditoDias" value="0" />
				<!--<img src="'.base_url().'img/clientes.png" onclick="formularioClientesVentas()" title="Nuevo cliente" style="width:2.4vh" />-->
				
				'.($tiendaLocal=='0'?'<input type="button" value="Nuevo cliente" class="botonPuntoVenta"  style="margin-left:1vh" id="btnRecargar" onclick="formularioClientesVentas()">':'').'
				
			</td>
			
			<td style="display: none">
				<input type="text" class="cajas" id="txtBuscarProveedor" style="width:28vh; height: 4.2vh; font-size: 2.5vh" placeholder="Proveedor"  tabindex="-1" />
			</td>
			
			<td style="display: none">
				<!--<input type="text" class="cajas" id="txtBuscarUsuarioPunto" style="width:28vh; height: 3.5vh; font-size: 2.2vh" placeholder="Vendedor"  tabindex="-1" />
				<input type="hidden" id="txtIdUsuarioVendedorPunto" name="txtIdUsuarioVendedorPunto" value="0"/>-->
				
				<select class="cajas" id="txtIdUsuarioVendedorPunto" name="txtIdUsuarioVendedorPunto" style="width:28vh; height: 4.2vh; font-size: 2.5vh" tabindex="-1">
					<option value="0">Seleccione</option>';
					
					foreach($usuarios as $row)
					{
						echo '<option value="'.$row->idUsuario.'">'.$row->usuario.'</option>';
					}
				
				echo'
				</select>
				
			</td>
			
		</tr>
	</table>
	
	
	
	<div id="obtenerProductosVenta" align="right" class="productosPuntoVenta">
	</div>
	
	<div align="right" style="margin-top:0vh">';
		

		
		echo'
		
		<input type="button" value="'.obtenerNombrePrecio(3).'(F7)" class="botonPuntoVenta"  style="margin-right:1vh" id="btnPrecio1" onclick="accesoPrecioPermiso()">
		
		'.($registroVentas=='1'?'<input type="button" value="Cotización(F6)" class="botonPuntoVenta"  style="margin-right:1vh" id="btnCotizaciones" onclick="formularioProcesarCotizacion()">':'').'
		
		<input type="button" value="Recargar(F5)" class="botonPuntoVenta"  style="margin-right:1vh" id="btnRecargar" onclick="recargarPaginaVenta()">
		<input type="button" value="Cancelar(F2)" class="botonPuntoVenta"  style="margin-right:1vh" id="btnCancelarVenta" onclick="formularioVentas()">
		'.($registroVentas=='1'?'<input type="button" value="Cobrar(F3)" class="botonPuntoVenta"  style="margin-right:7vh" id="btnCobros" onclick="formularioCobros()" >':'').'
		
	</div>
</div>
</form>';