$(document).ready(function()
{
	$("#ventanaEditarSeguimiento").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:600,
		width:800,
		modal:true,
		resizable:false,
		
		buttons: 
		{
			'Borrar': function() 
			{
				if($('#txtPermisoBorrar').val()=="1")
				{
					borrarSeguimientoCrm()			 
				}
				else
				{
					notify('Sin permisos para borrar',500,5000,'error',30,5);
				}
			},
			'Editar': function() 
			{
				if($('#txtPermisoEditar').val()=="1")
				{
					editarSeguimientoCrm();
				}
				else
				{
					notify('Sin permisos para editar',500,5000,'error',30,5);
				}
			},
		},
		close: function()
		{
			$("#obtenerSeguimientoEditar").html('');
			detalleCita	= false;
		}
	});
});

function obtenerSeguimientoEditar(idSeguimiento)
{
	detalleCita=true;
	
	$("#ventanaEditarSeguimiento").dialog("open");
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSeguimientoEditar').html('<img src="'+ img_loader +'"/> Obteniendo los detalles del seguimiento...');
		},
		type:"POST",
		url:base_url+'clientes/obtenerSeguimientoEditar',
		data:
		{
			"idSeguimiento":idSeguimiento,
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerSeguimientoEditar').html(data)
		},
		error:function(datos)
		{
			$('#obtenerSeguimientoEditar').html('');
			notify('Error al obtener el seguimiento',500,5000,'error',30,5);
		}
	});		
}
