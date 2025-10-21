function cargarCompraEgreso(compra)
{
	$("#txtIdProveedor").val(compra.idProveedor)
	$("#txtIdCompra").val(compra.idCompras)
	
	$("#txtDescripcionProducto").val(compra.producto)
	
	$("#txtBuscarProveedor").val(compra.proveedor)
	
	total	= compra.total-compra.pagado;
	tasa	= obtenerNumeros(compra.ivaPorcentaje);

	subTotal = total / (1+(tasa/100));
	
	$("#selectIva").val(redondear(tasa))
	$("#txtImporte").val(redondear(subTotal))
	$("#txtTotalIva").val(redondear(total-subTotal));
	$("#txtTotal").val(redondear(total));
	
	$("#txtSaldoCompra").val(redondear(total));
	$("#txtTasaImpuestoCompra").val(redondear(tasa));
	
	$("#lblSaldoCompra").html('Saldo: $'+redondear(total));
	
	//$("#txtDescripcionProducto,#txtImporte").attr('readonly',true);
	$("#txtBuscarProveedor").attr('readonly',true);
}