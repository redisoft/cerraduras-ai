
//DEVUELTOS
$(document).ready(function()
{
	$("#ventanaDevueltosControl").dialog(
	{
		autoOpen:false,
		height:500,
		width:850,
		modal:true,
		resizable:false,
		show: { effect: "scale", duration: 500 },
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Aceptar': function() 
			{
				registrarDevueltosControl()				  	  
			},
		},
		close: function() 
		{
			$("#obtenerDevueltosControl").html('');
		}
	});
});

function obtenerDevueltosControl(idSalida)
{
	$('#ventanaDevueltosControl').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerDevueltosControl').html('<img src="'+ img_loader +'"/> Obteniendo detalles de control...');
		},
		type:"POST",
		url:base_url+"materiales/obtenerDevueltosControl",
		data:
		{
			"idSalida":idSalida,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerDevueltosControl').html(data);

			re	= obtenerNumero($('#txtNumeroMateriales').val());
			
		},
		error:function(datos)
		{
			notify('Error al obtener los detalles de control',500,5000,'error',30,3)
			$("#obtenerDevueltosControl").html('');	
		}
	});
}

function registrarDevueltosControl()
{
	for(i=1;i<=obtenerNumero($('#txtNumeroMateriales').val());i++)
	{
		if(obtenerNumero($('#txtCantidadDevuelto'+i).val()) > obtenerNumero($('#txtCantidadSalida'+i).val()))
		{
			notify('La cantiedad excede la salida',500,5000,'',30,5)
			return;
		}
	}
	
	if(!confirm('¿Realmente desea continuar con el registro?')) return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#registrandoDevueltos').html('<img src="'+ img_loader +'"/> Registrando devueltos, por favor espere...');
		},
		type:"POST",
		url:base_url+"materiales/registrarDevueltosControl",
		data:$('#frmDevueltosControl').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoDevueltos').html('');
			data	= eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
				break;
				
				case "1":
					notify(data[1],500,5000,'',30,5)
					$('#ventanaDevueltosControl').dialog('close');
					obtenerSalidasControl()
				break;
			}
		},
		error:function(datos)
		{
			$('#registrandoDevueltos').html('');
			notify('Error al editar la la salida',500,5000,'error',30,3);	
		}
	});
}

