//CATEGORÍAS
$(document).ready(function()
{	
	$("#ventanaSubCategorias").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:750,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Registrar': function() 
			{
				formularioSubCategorias()
			},
		},
		close: function() 
		{
			$('#obtenerSubCategorias').html('');
		}
	});
	
	$("#ventanaFormularioSubCategorias").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				registrarSubCategoria()
			},
		},
		close: function() 
		{
			$('#formularioSubCategorias').html('');
		}
	});
	
	$("#ventanaEditarSubCategoria").dialog(
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
				editarSubCategoria();
			},
		},
		close: function() 
		{
			$('#obtenerSubCategoria').html('');
		}
	});
});

function obtenerSubCategorias(idCategoria)
{
	$("#ventanaSubCategorias").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerSubCategorias').html('<img src="'+ img_loader +'"/> Obteniendo detalles de subcategorías...');},
		type:"POST",
		url:base_url+'configuracion/obtenerSubCategorias',
		data:
		{
			idCategoria:idCategoria
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerSubCategorias").html(data);
		},
		error:function(datos)
		{
			$("#obtenerSubCategorias").html('');
		}
	});
}


function formularioSubCategorias()
{
	$("#ventanaFormularioSubCategorias").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioSubCategorias').html('<img src="'+ img_loader +'"/> Obteniendo detalles de formulario...');},
		type:"POST",
		url:base_url+'configuracion/formularioSubCategorias',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioSubCategorias").html(data);
		},
		error:function(datos)
		{
			$("#formularioSubCategorias").html('');
		}
	});
}

function registrarSubCategoria()
{
	if(!camposVacios($('#txtSubCategoria').val()))
	{
		notify('El nombre de la subcategoría es necesario',500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoSubCategoria').html('<img src="'+ img_loader +'"/>Registrando la subcategoría, por favor espere...');},
		type:"POST",
		url:base_url+"configuracion/registrarSubCategoria",
		data:$('#frmSubCategorias').serialize()+'&idCategoria='+$('#txtIdCategoria').val(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoSubCategoria").html("");
			
			data	= eval(data)
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					notify(data[1],500,5000,'',30,5);
					obtenerSubCategorias($('#txtIdCategoria').val());
					obtenerCategorias();
					$("#ventanaFormularioSubCategorias").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la subcategoría',500,5000,'error',30,3);
			$("#registrandoSubCategoria").html("");	
		}
	});				  	  
}

function obtenerSubCategoria(idSubCategoria)
{
	$("#ventanaEditarSubCategoria").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerCategoria').html('<img src="'+ img_loader +'"/> Obteniendo detalles de subcategoría...');},
		type:"POST",
		url:base_url+'configuracion/obtenerSubCategoria',
		data:
		{
			"idSubCategoria":idSubCategoria
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerSubCategoria").html(data);
		},
		error:function(datos)
		{
			$("#obtenerSubCategoria").html('Error al obtener los detalles de la subcategoría');
		}
	});
}

function editarSubCategoria()
{
	if(!camposVacios($('#txtSubCategoria').val()))
	{
		notify('El nombre de la subcategoría es necesario',500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro de la subcategoría?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoSubCategoria').html('<img src="'+ img_loader +'"/> Editando subcategoría...');},
		type:"POST",
		url:base_url+'configuracion/editarSubCategoria',
		data:$('#frmSubCategorias').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoSubCategoria').html('');
			
			data	= eval(data)
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
					
				break;
				case "1":
					notify(data[1],500,5000,'',30,5);
					obtenerSubCategorias($('#txtIdCategoria').val());
					$("#ventanaEditarSubCategoria").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('El registro de la subcategoría no se ha modificado',500,5000,'error',30,3);
			$("#editandoSubCategoria").html('');
		}
	});
}

function borrarSubCategoria(idSubCategoria)
{
	if(!confirm('¿Realmente desea borrar el registro de la subcategoría?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoSubCategorias').html('<img src="'+ img_loader +'"/> Borrando subcategoría...');},
		type:"POST",
		url:base_url+'configuracion/borrarSubCategoria',
		data:
		{
			idSubCategoria:idSubCategoria
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoSubCategorias').html('');

			switch(data[0])
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,3);
					
				break;
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerSubCategorias($('#txtIdCategoria').val());
				break;
			}
		},
		error:function(datos)
		{
			notify('El registro de la subcategoría no se ha modificado',500,5000,'error',30,3);
			$("#procesandoSubCategorias").html('');
		}
	});
}
