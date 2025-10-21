//CATEGORÍAS
$(document).ready(function()
{
	obtenerMarcas();
	
	$("#ventanaMarcas").dialog(
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
				registrarMarca()
			},
		},
		close: function() 
		{
			$('#formularioMarcas').html('');
		}
	});
	
	$("#ventanaEditarMarca").dialog(
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
				editarMarca();
			},
		},
		close: function() 
		{
			$('#obtenerMarca').html('');
		}
	});
});

function obtenerMarcas()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerMarcas').html('<img src="'+ img_loader +'"/> Obteniendo detalles de marcas...');},
		type:"POST",
		url:base_url+'catalogos/obtenerMarcas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerMarcas").html(data);
		},
		error:function(datos)
		{
			$("#obtenerMarcas").html('');
		}
	});
}


function formularioMarcas()
{
	$("#ventanaMarcas").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioMarcas').html('<img src="'+ img_loader +'"/> Obteniendo detalles de marca...');},
		type:"POST",
		url:base_url+'catalogos/formularioMarcas',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioMarcas").html(data);
		},
		error:function(datos)
		{
			$("#formularioMarcas").html('');
		}
	});
}

function registrarMarca()
{
	if(!camposVacios($('#txtMarca').val()))
	{
		notify('El nombre de la marca es necesario',500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoMarca').html('<img src="'+ img_loader +'"/>Registrando la marca, por favor espere...');},
		type:"POST",
		url:base_url+"catalogos/registrarMarca",
		data:$('#frmMarcas').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoMarca").html("");
			
			data	= eval(data)
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					notify(data[1],500,5000,'',30,5);
					obtenerMarcas();
					$("#ventanaMarcas").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la marca',500,5000,'error',30,3);
			$("#registrandoMarca").html("");	
		}
	});				  	  
}

function obtenerMarca(idMarca)
{
	$("#ventanaEditarMarca").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerMarca').html('<img src="'+ img_loader +'"/> Obteniendo detalles de marca...');},
		type:"POST",
		url:base_url+'catalogos/obtenerMarca',
		data:
		{
			"idMarca":idMarca
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerMarca").html(data);
		},
		error:function(datos)
		{
			$("#obtenerMarca").html('Error al obtener los detalles de la marca');
		}
	});
}

function editarMarca()
{
	if(!camposVacios($('#txtMarca').val()))
	{
		notify('El nombre de la marca es necesario',500,5000,'error',30,3);
		return
	}
	
	if(!confirm('¿Realmente desea editar el registro de la marca?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoMarca').html('<img src="'+ img_loader +'"/> Editando marca...');},
		type:"POST",
		url:base_url+'catalogos/editarMarca',
		data:$('#frmMarcas').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoMarca').html('');
			
			data	= eval(data)
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
					
				break;
				case "1":
					notify(data[1],500,5000,'',30,5);
					obtenerMarcas();
					$("#ventanaEditarMarca").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('El registro de la marca no se ha modificado',500,5000,'error',30,3);
			$("#editandoMarca").html('');
		}
	});
}

function borrarMarca(idMarca)
{
	if(!confirm('¿Realmente desea borrar el registro de la marca?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoMarcas').html('<img src="'+ img_loader +'"/> Borrando marca...');},
		type:"POST",
		url:base_url+'catalogos/borrarMarca',
		data:
		{
			idMarca:idMarca
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoMarcas').html('');

			switch(data[0])
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,3);
					
				break;
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerMarcas();
				break;
			}
		},
		error:function(datos)
		{
			notify('El registro de la marca no se ha modificado',500,5000,'error',30,3);
			$("#procesandoMarcas").html('');
		}
	});
}
