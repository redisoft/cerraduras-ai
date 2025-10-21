function cargarVentaIngreso(venta)
{
	$("#txtIdCliente").val(venta.idCliente)
	$("#txtIdVenta").val(venta.idCotizacion)
	
	$("#txtDescripcionProducto").val(venta.producto)
	
	if(sistemaActivo=="IEXE")
	{
		$("#txtBuscarCliente").val("Alumno: " + venta.alumno + ", Cliente: " + venta.empresa)
	}
	else
	{
		$("#txtBuscarCliente").val(venta.empresa)
	}
	
	total	= venta.total-venta.pagado;
	tasa	= obtenerNumeros(venta.tasa);

	subTotal = total / (1+(tasa/100));
	
	$("#selectIva").val(redondear(tasa))
	$("#txtImporte").val(redondear(subTotal))
	$("#txtTotalIva").val(redondear(total-subTotal));
	$("#txtTotal").val(redondear(total));
	
	$("#txtSaldoVenta").val(redondear(total));
	$("#txtTasaImpuestoVenta").val(redondear(tasa));
	
	$("#lblSaldoVenta").html('Saldo: $'+redondear(total));
	
	//$("#txtDescripcionProducto,#txtImporte").attr('readonly',true);
	$("#txtBuscarCliente").attr('readonly',true);
}