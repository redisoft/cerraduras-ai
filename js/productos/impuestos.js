function calcularImpuestoProducto(ban)
{
	impuestos 	= new String($('#selectImpuestos').val());
	impuesto	= impuestos.split('|');
	tasa		= obtenerNumeros(impuesto[1])/100;		

	if(ban==0)
	{
		precio			= obtenerNumeros($('#txtPrecioA').val());	
		precioImpuesto	= (precio*tasa)+precio	
		
		$('#txtPrecioImpuestos').val(redondear(precioImpuesto))
	}
	
	if(ban==1)
	{
		precioImpuesto		= obtenerNumeros($('#txtPrecioImpuestos').val());	
		precio				= precioImpuesto/(1+tasa)	
		
		$('#txtPrecioA').val(redondear(precio))
	}
}