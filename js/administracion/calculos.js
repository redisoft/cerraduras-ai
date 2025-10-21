//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//CALCULAR EL IVA PARA LOS IMPORTES
function calcularIvaImporteTotal()
{
	importe			= obtenerNumero($('#txtTotal').val());
	ivaPorcentaje	= obtenerNumero($('#selectIva').val());
	ivaPorcentaje	= ivaPorcentaje>0?ivaPorcentaje/100:0;
	subTotal		= importe / (1+ivaPorcentaje);
	iva				= importe-subTotal;
	
	$('#txtImporte').val(redondear(subTotal))
	$('#txtTotalIva').val(redondear(iva))
	$('#txtTotal').val(redondear(importe))
}

//CALCULAR EL IVA PARA LOS IMPORTES - DESDE EL SUBTOTAL
function calcularIvaImporte()
{
	importe			= obtenerNumero($('#txtImporte').val());
	ivaPorcentaje	= obtenerNumero($('#selectIva').val());
	ivaPorcentaje	= ivaPorcentaje>0?ivaPorcentaje/100:0;
	iva				= importe*ivaPorcentaje;
	total			= importe+iva;
	
	$('#txtImporte').val(redondear(importe))
	$('#txtTotalIva').val(redondear(iva))
	$('#txtTotal').val(redondear(total))
}