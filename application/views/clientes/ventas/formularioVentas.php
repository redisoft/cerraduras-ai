<script>
$("#txtBuscarCliente").autocomplete(
{
	source:base_url+'configuracion/obtenerClientes',
	
	select:function( event, ui)
	{
		$("#txtIdCliente").val(ui.item.idCliente);
		$("#txtPrecioCliente").val(ui.item.precio);
		obtenerBancos();
		
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
$clientePlaceholder = $cliente!=null ? $cliente->empresa : (sistemaActivo=='olyess' ? 'Seleccione cliente' : 'VENTAS AL PÚBLICO GENERAL');
$clienteId          = $cliente!=null ? $cliente->idCliente : (sistemaActivo=='olyess' ? 0 : 1);
$precioCliente      = $cliente!=null ? $cliente->precio : 1;
?>

<form id="frmVentasClientes" name="frmVentasClientes" action="javascript:formularioCobros()" class="pos-form">
	<input type="hidden" id="txtClaveDescuento" name="txtClaveDescuento" value="<?=$claveDescuento?>" />
	<input type="hidden" id="txtNumeroProductos" name="txtNumeroProductos" value="0" />
	<input type="hidden" id="txtTipoUsuarioActivo" name="txtTipoUsuarioActivo" value="<?=tipoUsuario?>" />
	<input type="hidden" id="txtVentasF4" name="txtVentasF4" value="<?=$ventasF4?>" />
	<input type="hidden" id="txtLimiteVentas" name="txtLimiteVentas" value="<?=$limiteVentas?>" />
	<input type="hidden" id="txtPrecioCliente" name="txtPrecioCliente" value="<?=$precioCliente?>" />
	<input type="hidden" id="txtTotalPrevio" value="0" />

	<div class="pos-layout">
		<section class="pos-panel pos-panel--cart">
			<header class="pos-panel__header">
				<div>
					<h2 class="pos-panel__title">Carrito</h2>
					<p class="pos-panel__subtitle">Productos agregados a la venta</p>
				</div>
			</header>

			<div class="listaVentas pos-cart">
				<div class="Error_validar" id="carritoVacio">Carrito de ventas vacio</div>
				<table class="admintable" id="tablaVentas">
					<tbody></tbody>
				</table>
			</div>

			<div class="pos-summary">
				<?php if(strlen($claveDescuento) > 0): ?>
					<button type="button" class="pos-summary__discount" onclick="accesoAsignarDescuento(0)" title="Asignar descuento">
						<img src="<?=base_url()?>img/descuento.png" alt="" aria-hidden="true" />
						<span>Aplicar descuento</span>
					</button>
				<?php endif; ?>
				<span id="filaTotal" class="pos-summary__total">TOTAL: $0.00</span>
				<div class="pos-summary__breakdown">
					<span id="filaSubTotal" class="pos-summary__meta">SUBTOTAL: $0.00</span>
					<span id="filaDescuento" class="pos-summary__meta">DESC: $0.00</span>
					<span id="filaIva" class="pos-summary__meta">IMPUESTOS: $0.00</span>
				</div>
				<input type="hidden" id="txtDescuentoPorcentaje0" value="0" />
				<input type="hidden" id="txtDescuentoProducto0" value="0" />
			</div>

			<div class="pos-client">
				<label for="txtBuscarCliente">Cliente</label>
				<div class="pos-client__field">
					<input
						type="text"
						class="cajas"
						id="txtBuscarCliente"
						placeholder="<?=$clientePlaceholder?>"
					/>
					<input type="hidden" id="txtIdCliente" name="txtIdCliente" value="<?=$clienteId?>" />
					<input type="hidden" id="txtCreditoDias" name="txtCreditoDias" value="0" />
					<button type="button" class="pos-client__new" onclick="formularioClientes('venta')" title="Nuevo cliente">
						<img src="<?=base_url()?>img/clientes.png" alt="Nuevo cliente" />
					</button>
				</div>
			</div>

			<div class="pos-meta">
				<div class="pos-meta__item">
					<label for="txtFechaVenta">Fecha</label>
					<input type="text" class="cajas" id="txtFechaVenta" name="txtFechaVenta" value="<?=date('Y-m-d H:i')?>" />
				</div>
				<div class="pos-meta__item pos-meta__item--full">
					<label for="txtObservacionesVenta">Observaciones</label>
					<textarea id="txtObservacionesVenta" name="txtObservacionesVenta" class="TextArea" placeholder="Observaciones"></textarea>
				</div>
			</div>
		</section>

		<section class="pos-panel pos-panel--catalog">
			<header class="pos-panel__header pos-panel__header--compact">
				<div>
					<h2 class="pos-panel__title">Productos y servicios</h2>
					<p class="pos-panel__subtitle">Busca y agrega artículos al carrito</p>
				</div>
			</header>

			<input type="hidden" id="txtIdLinea" name="txtIdLinea" value="0" />

			<div class="pos-filters">
				<div class="pos-filters__item">
					<label for="selectLineas">Línea</label>
					<select class="cajas" id="selectLineas" name="selectLineas" onchange="obtenerProductosVenta(); obtenerSubLineasCatalogo()">
						<option value="0">Seleccione línea</option>
						<?php foreach($lineas as $row): ?>
							<option value="<?=$row->idLinea?>"><?=$row->nombre?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="pos-filters__item" id="obtenerSubLineas">
					<label for="selectSubLineas">Sublinea</label>
					<select class="cajas" id="selectSubLineas" name="selectSubLineas" <?=sistemaActivo=='pinata'?'style="display:none"':''?> onchange="obtenerProductosVenta()">
						<option value="0">Seleccione sublinea</option>
						<?php foreach($sublineas as $row): ?>
							<option value="<?=$row->idSubLinea?>"><?=$row->nombre?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="pos-filters__item pos-filters__item--grow">
					<label for="txtBuscarProducto">Buscar producto</label>
					<input type="text" class="cajas" id="txtBuscarProducto" placeholder="Buscar productos / servicios" />
				</div>

				<div class="pos-filters__item pos-filters__item--grow">
					<label for="txtBuscarCodigo">Buscar por código</label>
					<input type="text" class="cajas" id="txtBuscarCodigo" placeholder="Buscar por código de barras, código" />
				</div>
			</div>

			<div id="obtenerProductosVenta" class="productosPuntoVenta" align="right"></div>

			<div class="pos-actions">
				<?php if(sistemaActivo=='olyess'): ?>
					<input type="button" value="Pedidos" class="botonPuntoVenta" id="btnCobros" onclick="formularioPedidos()" />
				<?php endif; ?>
				<input type="button" value="Cancelar(F2)" class="botonPuntoVenta" id="btnCancelarVenta" onclick="formularioVentas()" />
				<input type="button" value="Cobrar(F3)" class="botonPuntoVenta" id="btnCobros" onclick="formularioCobros()" />
			</div>
		</section>
	</div>
</form>

<script>
	$("#txtFechaVenta").timepicker();
</script>
