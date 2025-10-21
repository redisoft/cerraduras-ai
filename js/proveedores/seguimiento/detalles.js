
//DETALLES DE SEGUIMIENTO
$(document).ready(function()
{
	$("#ventanaDetallesSeguimiento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:550,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');				 
			}
		},
		close: function()
		{
			$("#detallesSeguimiento").html('');
		}
	});
})

function detallesSeguimiento(idSeguimiento)
{
	$("#ventanaDetallesSeguimiento").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#detallesSeguimiento').html('<img src="'+ img_loader +'"/>Cargando los detalles del seguimiento...');
		},
		type:"POST",
		url:base_url+'proveedores/obtenerSeguimiento',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#detallesSeguimiento').html(data)
		},
		error:function(datos)
		{
			$('#detallesSeguimiento').html('');
			notify('Error al obtener los detalles de seguimiento',500,5000,'error',30,5);
		}
	});		
}