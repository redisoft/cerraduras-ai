
filaUnidades=1;

function cargarGraficarUnidades()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#cargarGraficarUnidades').html('<img src="'+ img_loader +'"/> Espere por favor ...');},
		type:"POST",
		url:base_url+'principal/formularioGraficaUnidades',
		data:
		{
			//"nombreProducto":$("#txtBusquedaProducto").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#cargarGraficarUnidades").html(data);
		},
		error:function(datos)
		{
			$("#cargarGraficarUnidades").html('Error al obtener las opciones para graficar');
		}
	});				  	  
}

function verificarProductoGrafica(idProducto)
{
	for(i=1;i<=filaUnidades;i++)
	{
		if(!isNaN($('#txtIdProducto'+i).val()))
		{
			if(parseInt(idProducto)==parseInt($('#txtIdProducto'+i).val()))
			{
				return false;
			}
		}
	}
	
	return true;
}

function realizarBusquedaUnidades()
{
	productos=new Array();
	
	if(filaUnidades==1)
	{
		notify('Por favor seleccione por al menos un producto',500,5000,'error',30,3);
		return;
	}
	
	p=0;
	
	for(i=1;i<=filaUnidades;i++)
	{
		if(!isNaN($('#txtIdProducto'+i).val()))
		{
			productos[p]=$('#txtIdProducto'+i).val();
			p++;
		}
	}
	
	$('#graficandoUnidades').fadeIn();
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#graficandoUnidades').html('<img src="'+ img_loader +'"/> Obteniendo los datos para graficarlos, por favor espere ...');
			$("#graficaVentas").html('<img src="'+ img_loader +'" /> Obteniendo los datos para graficarlos, por favor espere');
		},
		type:"POST",
		url:base_url+'principal/graficarUnidades',
		data:
		{
			"productos":productos,
		},
		datatype:"script",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('El sistema no encontro resultados para los productos seleccionados',500,5000,'error',30,3);
				$('#graficandoUnidades').fadeOut();
				$("#graficaVentas").html('Sin datos para graficar');
				break;
				
				default:
				$('#graficandoUnidades').fadeOut();
				//$("#graficaVentas").html('<img src="'+ img_loader +'" />');
				notify('En un momento se mostrara la grafica con la información de los productos',500,4000,'',30,3);
				$('#graficaVentas').html(data);
				return 1;
				break;
			}
			
		},
		error:function(datos)
		{
			$('#graficandoUnidades').fadeOut();
			notify('El sistema no encontro resultados para los productos seleccionados',500,5000,'error',30,3);
		}
	});			
}

function cerrarVentanaGrafica()
{
	//alert('Jajaja');
	//$('ventanaGraficarUnidades').dialog('close');
}

$(document).ready(function()
{
	$("#graficarUnidades").click(function(e)
	{
		$('#ventanaGraficarUnidades').dialog('open');
	});

	$("#ventanaGraficarUnidades").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:400,
		width:600,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				
				realizarBusquedaUnidades();
				
				$('#graficandoUnidades').html('');
				$(this).dialog('close');
			},
		},
		close: function() 
		{
			$("#realizandoVenta").fadeOut();
		}
	});
});

//============================================================================================================================//
	
	fila=1;
	
	function comprobarDuplicidad(n)
	{
		for(i=0;i<=fila;i++)
		{
			if(!isNaN($('#idProducto'+i).val()))
			{
				if($('#agregar'+n).val()==$('#idProducto'+i).val())
				{
					return 0;
				}
			}
		}
		
		return 1;
	}
	
	function quitarProductoKit(n)
	{
		$('#filaProducto'+n).remove();
		calcularTotales();
		calcularCambio();
	}
	

	