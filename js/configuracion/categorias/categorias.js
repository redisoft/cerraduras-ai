//CATEGORÍAS
$(document).ready(function()
{
	obtenerCategorias();
	
	$("#ventanaCategorias").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:600,
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
				registrarCategoria()
			},
		},
		close: function() 
		{
			$('#formularioCategorias').html('');
		}
	});
	
	$("#ventanaEditarCategoria").dialog(
	{
		autoOpen:false,
		height:200,
		width:600,
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
				editarCategoria();
			},
		},
		close: function() 
		{
			$('#obtenerCategoria').html('');
		}
	});
});

function obtenerCategorias()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerCategorias').html('<img src="'+ img_loader +'"/> Obteniendo detalles de categorías...');},
		type:"POST",
		url:base_url+'configuracion/obtenerCategorias',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerCategorias").html(data);
		},
		error:function(datos)
		{
			$("#obtenerCategorias").html('');
		}
	});
}


function formularioCategorias()
{
	$("#ventanaCategorias").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioCategorias').html('<img src="'+ img_loader +'"/> Obteniendo detalles de categoría...');},
		type:"POST",
		url:base_url+'configuracion/formularioCategorias',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioCategorias").html(data);
		},
		error:function(datos)
		{
			$("#formularioCategorias").html('');
		}
	});
}

function registrarCategoria()
{
	if(!camposVacios($('#txtCategoria').val()))
	{
		notify('El nombre de la categoria es necesario',500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoCategoria').html('<img src="'+ img_loader +'"/>Registrando la categoría, por favor espere...');},
		type:"POST",
		url:base_url+"configuracion/registrarCategoria",
		data:$('#frmCategorias').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoCategoria").html("");
			
			data	= eval(data)
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					notify(data[1],500,5000,'',30,5);
					obtenerCategorias();
					$("#ventanaCategorias").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la categoría',500,5000,'error',30,3);
			$("#registrandoCategoria").html("");	
		}
	});				  	  
}

function obtenerCategoria(idCategoria)
{
	$("#ventanaEditarCategoria").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerCategoria').html('<img src="'+ img_loader +'"/> Obteniendo detalles de categoria...');},
		type:"POST",
		url:base_url+'configuracion/obtenerCategoria',
		data:
		{
			"idCategoria":idCategoria
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerCategoria").html(data);
		},
		error:function(datos)
		{
			$("#obtenerCategoria").html('Error al obtener los detalles de la categoria');
		}
	});
}

function editarCategoria()
{
	if(!camposVacios($('#txtCategoria').val()))
	{
		notify('El nombre de la categoría es necesario',500,5000,'error',30,3);
		return
	}
	
	if(!confirm('¿Realmente desea editar el registro de la categoría?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoCategoria').html('<img src="'+ img_loader +'"/> Editando categoría...');},
		type:"POST",
		url:base_url+'configuracion/editarCategoria',
		data:$('#frmCategorias').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoCategoria').html('');
			
			data	= eval(data)
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
					
				break;
				case "1":
					notify(data[1],500,5000,'',30,5);
					obtenerCategorias();
					$("#ventanaEditarCategoria").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('El registro de la categoria no se ha modificado',500,5000,'error',30,3);
			$("#editandoCategoria").html('');
		}
	});
}

function borrarCategoria(idCategoria)
{
	if(!confirm('¿Realmente desea borrar el registro de la categoría?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoCategorias').html('<img src="'+ img_loader +'"/> Borrando categoría...');},
		type:"POST",
		url:base_url+'configuracion/borrarCategoria',
		data:
		{
			idCategoria:idCategoria
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoCategorias').html('');

			switch(data[0])
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,3);
					
				break;
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerCategorias();
				break;
			}
		},
		error:function(datos)
		{
			notify('El registro de la categoria no se ha modificado',500,5000,'error',30,3);
			$("#procesandoCategorias").html('');
		}
	});
}
