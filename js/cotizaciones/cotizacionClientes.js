$(document).ready(function()
{
	$("#ventanaCotizaciones").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:650,
		width:1100,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				registrarCotizacion()		  	  
			},
		},
		close: function() 
		{
			$("#formularioCotizacionesClientes").html('');
		}
	});
});

function registrarCotizacion()
{
	mensaje			="";
	productos		=new Array();
	cantidad		=new Array();
	totales			=new Array();
	precioProducto	=new Array();
	servicios		=new Array();
	fechas			=new Array();
	nombres			=new Array();
	
	m=0;

	
	if($("#txtSubTotal").val()=="0" || parseFloat($("#txtSubTotal").val())=="0")
	{
		mensaje+="No se han agregado productos para la cotización <br />";
	}

	if($('#txtIdCliente').val()=="0")
	{
		mensaje+="Debe seleccionar un cliente <br />";
	}
	
	v=0;
	
	for(i=0;i<fila;i++)
	{
		precio=parseFloat($('#totalProducto'+i).val())
		
		if(!isNaN(precio))
		{
			totalKit+=precio
			
			productos[v]		=$('#idProducto'+i).val();
			cantidad[v]			=$('#cantidadProducto'+i).val();
			totales[v]			=$('#totalProducto'+i).val();
			precioProducto[v]	=$('#precioProducto'+i).val();
			servicios[v]		=$('#txtIdPeriodo'+i).val();
			fechas[v]			=$('#txtFechaInicio'+i).val();
			
			nombres[v]			=$('#txtNombreProducto'+i).val();
			
			
			if($('#txtNombreProducto'+i).val()=="")
			{
				notify('El nombre del producto es incorrecto',500,5000,'error',30,0);
				$('#txtNombreProducto'+i).focus()
				return;
			}
			
			if($('#txtFechaInicio'+i).val()=="")
			{
				notify('Por favor configure la fecha de inicio correctamente de los servicios',500,5000,'error',30,0);
				$('#txtFechaInicio'+i).focus()
				return;
			}
			
			v++;
		}
	}
	
	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,0);
		return;
	}
	
	if(!confirm('¿Realmente deseea realizar la cotización?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#realizandoCotizacion').html('<img src="'+ img_loader +'"/> Se esta realizando la cotización, por favor tenga paciencia ...');},
		type:"POST",
		url:base_url+'clientes/realizarCotizacion',
		data:
		{
			"productos":			productos,
			"cantidad":				cantidad,
			"preciosTotales":		totales,
			"precioProducto":		precioProducto,
			"servicios":			servicios,
			fechas:					fechas,
			nombres:				nombres,
			"iva":					$("#txtIva").val(),
			"subTotal":				$("#txtSubTotal").val(),
			"total":				$("#txtTotal").val(),
			"idCliente":			$("#txtIdCliente").val(),
			"idDivisa":				$("#selectDivisas").val(),
			"fechaCotizacion":		$("#txtFechaCotizacion").val(),
			"fechaEntrega":			$("#txtFechaEntrega").val(),
			"comentarios":			$("#txtComentarios").val(),
			"serie":				$("#txtSerie").val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				$("#realizandoCotizacion").html('');
				notify('Error al realizar la cotización',500,5000,'error',30,3);
				break;
				
				case "1":
				$("#realizandoCotizacion").html('');
				window.location.href=base_url+'clientes';
				break;
			}
		},
		error:function(datos)
		{
			$("#realizandoCotizacion").html('');
			notify('Error al realizar la cotización, por favor verifique la conexión a internet',500,5000,'error',30,3);
		}
	});		
}

function formularioCotizacionesClientes()
{
	$('#ventanaCotizaciones').dialog('open');
	fila=1;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioCotizacionesClientes').html('<img src="'+ img_loader +'"/> Obteniendo el formulario de cotizaciones...');
		},
		type:"POST",
		url:base_url+'clientes/formularioCotizacionesClientes',
		data:
		{	
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#formularioCotizacionesClientes').html(data);
			obtenerProductosVenta();
		},
		error:function(datos)
		{
			$('#formularioCotizacionesClientes').html('');
		}
	});		
}
