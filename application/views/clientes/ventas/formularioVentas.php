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
		<aside class="pos-cart">
			<header class="pos-cart__header">
				<h2>Carrito</h2>
				<p>Productos agregados a la venta</p>
			</header>

			<div class="listaVentas pos-cart__list">
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
		</aside>

		<section class="pos-search">
			<h2>Presione Enter para buscar</h2>

			<div class="pos-search__inputs">
				<label for="txtBuscarCodigo">
					Código
					<input type="text" class="cajas" id="txtBuscarCodigo" placeholder="Buscar por código de barras, código" />
				</label>
				<label for="txtBuscarProducto">
					Nombre
					<input type="text" class="cajas" id="txtBuscarProducto" placeholder="Buscar productos / servicios" />
				</label>
			</div>

			<div class="pos-search__filters">
				<label for="selectLineas">
					Línea
					<select class="cajas" id="selectLineas" name="selectLineas" onchange="obtenerProductosVenta(); obtenerSubLineasCatalogo()">
						<option value="0">Seleccione línea</option>
						<?php foreach($lineas as $row): ?>
							<option value="<?=$row->idLinea?>"><?=$row->nombre?></option>
						<?php endforeach; ?>
					</select>
				</label>

				<label for="selectSubLineas" id="obtenerSubLineas">
					Sublinea
					<select class="cajas" id="selectSubLineas" name="selectSubLineas" <?=sistemaActivo=='pinata'?'style="display:none"':''?> onchange="obtenerProductosVenta()">
						<option value="0">Seleccione sublinea</option>
						<?php foreach($sublineas as $row): ?>
							<option value="<?=$row->idSubLinea?>"><?=$row->nombre?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>

			<div class="pos-search__cliente">
				<label for="txtBuscarCliente">
					Cliente
					<input
						type="text"
						class="cajas"
						id="txtBuscarCliente"
						placeholder="<?=$clientePlaceholder?>"
					/>
				</label>
				<input type="hidden" id="txtIdCliente" name="txtIdCliente" value="<?=$clienteId?>" />
				<input type="hidden" id="txtCreditoDias" name="txtCreditoDias" value="0" />
				<button type="button" class="btn-secundario" onclick="formularioClientes('venta')" title="Nuevo cliente">
					Nuevo cliente
				</button>
			</div>

			<input type="hidden" id="txtIdLinea" name="txtIdLinea" value="0" />
		</section>

		<section class="pos-products">
			<header class="pos-products__header">
				<span class="pos-products__title">Listado de productos</span>
			</header>

			<div id="obtenerProductosVenta" class="pos-products__table productosPuntoVenta"></div>

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
