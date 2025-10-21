//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//DESCUENTOS DE PRODUCTOS
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
filaDescuento=0;
function formularioDescuentoProducto(i)
{
	$("#ventanaAsignarDescuento").dialog('open');
	filaDescuento=i;
	
	$('#txtAsignarDescuento').val(redondear($('#txtDescuentoPorcentaje'+i).val()));
	
	$('#txtAsignarDescuento').select();
}

$(document).ready(function()
{
	$("#ventanaAsignarDescuento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				asignarDescuentoProducto()
			},
		},
		close: function() 
		{
			$("#txtAsignarDescuento").val('');
			filaDescuento=0;
		}
	});
});


function asignarDescuentoProducto()
{
	descuentoPorcentaje	= parseFloat($('#txtAsignarDescuento').val());
	
	if(isNaN(descuentoPorcentaje) || !comprobarNumeros(descuentoPorcentaje) || descuentoPorcentaje>99)
	{
		descuentoPorcentaje=0;
	}

	if(filaDescuento==0)
	{
		$('.descuentosProductos').val(descuentoPorcentaje);
		
		
		for(ii=0;ii<=fila;ii++)
		{
			
			calcularFilaProducto(ii);
		}
		
		//calcularSubTotal();
	}
	else
	{
		$('#txtDescuentoPorcentaje'+filaDescuento).val(descuentoPorcentaje);
		
		calcularFilaProducto(filaDescuento);
	}
	
	$("#ventanaAsignarDescuento").dialog('close');
}