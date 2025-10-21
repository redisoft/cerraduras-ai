function obtenerFolio()
{
	$.ajax(
	{
		async   : true,
		beforeSend:function(objeto)
		{
			$('#obtenerFolio').html('<img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo el folio del emisor');
		},
		type:	"POST",
		url:  	base_url+"facturacion/obtenerFolio",
		data: 
		{
			"idEmisor":	$('#selectEmisores').val(),
		},
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#obtenerFolio').html(data);
		},
		error: function(datos)
		{
			$('#obtenerFolio').html('');
			notify('Error al obtener el folio del emisor',500,5000,'error',34,4);
		}
	});
}

function obtenerFolioEmisor()
{
	$.ajax(
	{
		async   : true,
		beforeSend:function(objeto)
		{
			$('#obtenerFolioEmisor').html('<img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo el folio del emisor');
		},
		type:	"POST",
		url:  	base_url+"facturacion/obtenerFolio",
		data: 
		{
			"idEmisor":	$('#selectEmisoresGlobal').val(),
		},
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#obtenerFolioEmisor').html(data);
		},
		error: function(datos)
		{
			$('#obtenerFolioEmisor').html('');
			notify('Error al obtener el folio del emisor',500,5000,'error',34,4);
		}
	});
}


function obtenerFolioParcial()
{
	$.ajax(
	{
		async   : true,
		beforeSend:function(objeto)
		{
			$('#obtenerFolioParcial').html('<img src="'+base_url+'img/ajax-loader.gif"/>Obteniendo el folio del emisor');
		},
		type:	"POST",
		url:  	base_url+"facturacion/obtenerFolio",
		data: 
		{
			"idEmisor":	$('#selectEmisoresParcial').val(),
		},
		datatype: "html",
		success	: function(data, textStatus)
		{
			$('#obtenerFolioParcial').html(data);
		},
		error: function(datos)
		{
			$('#obtenerFolioParcial').html('');
			notify('Error al obtener el folio del emisor',500,5000,'error',34,4);
		}
	});
}

function calcularPorcentajeProductos()
{
	subTotal	= 0;
	iva			= parseFloat($('#porcentajeIva').val());
	
	if($('#selectCriterio').val()=="0")
	{
		porcentaje	=parseFloat($('#txtPorcentajeFacturar').val())/100;
		
		if(!comprobarNumeros($('#txtPorcentajeFacturar').val()) || porcentaje==0 || porcentaje>1)
		{
			porcentaje	=1;
			$('#txtPorcentajeFacturar').val(100)
		}
	
		for(i=1;i<=parseInt($('#txtNumeroProductos').val());i++)
		{
			$('#lblCantidad'+i).fadeIn();
			$('#txtCantidadFacturar'+i).fadeOut()
		
			precio				= parseFloat($('#txtPrecioProducto'+i).val());
			cantidad			= parseFloat($('#txtCantidadProducto'+i).val());
			
			
			cantidad			= cantidad*porcentaje;
			importe				= cantidad*precio;
			
			//SE AGREGO DESCUENTO
			descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentaje'+i).val());
			descuento			= descuentoPorcentaje>0?(descuentoPorcentaje/100)*importe:0;
			importe				-=descuento;
			//-----------------------------------------------------------------------------------
			
			subTotal			+=importe;
			
			$('#lblCantidad'+i).html(redondear(cantidad));
			$('#lblImporte'+i).html(redondear(importe));
			$('#txtCantidadFacturar'+i).val(redondear(cantidad));
			
			//alert($('#txtCantidadFacturar'+i).val())
			$('#lblDescuento'+i).html(redondear(descuento));
			$('#txtDescuentoProducto'+i).val(redondear(descuento));
		}
	}
	
	if($('#selectCriterio').val()=="1")
	{
		for(i=1;i<=parseInt($('#txtNumeroProductos').val());i++)
		{
			$('#lblCantidad'+i).fadeOut();
			$('#txtCantidadFacturar'+i).fadeIn()
			
			cantidad			=parseFloat($('#txtCantidadFacturar'+i).val());
			cantidadProducto	=parseFloat($('#txtCantidadProducto'+i).val());
			
			if(cantidad>cantidadProducto || !comprobarNumeros(cantidadProducto) || isNaN(cantidad) || cantidad==0)
			{
				cantidad	= cantidadProducto;
				$('#txtCantidadFacturar'+i).val(cantidad)
			}

			precio		= parseFloat($('#txtPrecioProducto'+i).val());
			importe		= cantidad*precio;
			
			//SE AGREGO DESCUENTO
			descuentoPorcentaje	= parseFloat($('#txtDescuentoPorcentaje'+i).val());
			descuento			= descuentoPorcentaje>0?(descuentoPorcentaje/100)*importe:0;
			importe				-=descuento;
			//-----------------------------------------------------------------------------------
			
			subTotal	+=importe;
			
			$('#lblImporte'+i).html(redondeo2decimales(importe));
			
			$('#lblDescuento'+i).html(redondear(descuento));
			$('#txtDescuentoProducto'+i).val(redondear(descuento));
		}
	}
	
	//SE AGREGO DESCUENTO
	descuentoPorcentaje	= parseFloat($('#porcentajeDescuento').val());
	descuento			= descuentoPorcentaje>0?subTotal*descuentoPorcentaje/100:0;
	subTotal			-=descuento;
	//-----------------------------------------------------------------------------------
	
	iva		= iva>0?iva/100:0;
	iva		= subTotal*iva;
	total	= iva+subTotal;
	
	$('#lblSubTotalParcial').html('$'+redondear(subTotal+descuento));
	$('#lblIva').html('$'+redondear(iva));
	$('#lblTotalParcial').html('$'+redondear(total));
	
	$('#txtSubTotalParcial').val(redondear(subTotal+descuento));
	$('#txtIvaParcial').val(redondear(iva));
	$('#txtTotalParcial').val(redondear(total));
	
	//SE AGREGO DESCUENTO
	$('#lblDescuento').html('$'+redondear(descuento));
	$('#txtDescuentoParcial').val(redondear(descuento));
	//-----------------------------------------------------------------------------------
	
}

function criterioFacturacion()
{
	switch($('#selectCriterio').val())
	{
		case "0":
			$('#txtPorcentajeFacturar').val(100);
			$('#filaPorcentaje').fadeIn();
			
		break;
		
		case "1":
			$('#filaPorcentaje').fadeOut();
		break;
	}
	
	calcularPorcentajeProductos();
}

function sugerirMetodoPago()
{
	$("#txtMetodoPagoTexto").val($("#txtMetodoPago option:selected").text());
}