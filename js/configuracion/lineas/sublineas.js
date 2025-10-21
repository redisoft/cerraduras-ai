$(document).ready(function()
{
	$("#ventanaSubLineas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:500,
		width:800,
		modal:true,
		resizable:false,
		buttons: 
		{
			Cancelar: function() 
			{
				$(this).dialog('close');				 
			},
			'Registrar': function() 
			{
				formularioSubLineas();
			},
		},
		close: function() 
		{
			$("#obtenerSubLineas").html(''); 
		}
	});
	
	$("#ventanaFormularioSubLineas").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:650,
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
				registrarSubLinea();
			},
		},
		close: function() 
		{
			$("#formularioSubLineas").html(''); 
		}
	});

	$("#ventanaEditarSubLinea").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 600 },
		height:200,
		width:650,
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
				editarSubLinea();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerSubLinea').html('');
		}
	});
});

function obtenerSubLineas(idLinea)
{
	$('#ventanaSubLineas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSubLineas').html('<img src="'+ img_loader +'"/> Obteniendo detalles de registros..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerSubLineas',
		data:
		{		
			idLinea:idLinea
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerSubLineas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos',500,5000,'error',30,3);
			$("#obtenerSubLineas").html('');
		}
	});
}

function formularioSubLineas()
{
	$('#ventanaFormularioSubLineas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioSubLineas').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar la sublinea..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioSubLineas',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioSubLineas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar la línea',500,5000,'error',30,3);
			$("#formularioSubLineas").html('');
		}
	});
}

function registrarSubLinea()
{
	mensaje="";
	
	if(!camposVacios($('#txtSubLinea').val()))
	{
		mensaje+="El nombre es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}

	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#registrandoLinea').html('<img src="'+ img_loader +'"/> Registrandos la sublinea..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarSubLinea',
		data:$('#frmSubLineas').serialize()+'&idLinea='+$('#txtIdLinea').val(),
		async: false,
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#registrandoLinea').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				
				break;
				
				case "1":
					obtenerSubLineas($('#txtIdLinea').val());
					notify('El registro ha sido exitoso',500,5000,'',30,5);
					$('#ventanaFormularioSubLineas').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la divisa',500,5000,'error',30,5);
			$("#registrandoLinea").html('');
		}
	});		
}


function obtenerSubLinea(idSubLinea)
{
	$('#ventanaEditarSubLinea').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerSubLinea').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar la sublinea..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerSubLinea',
		data:
		{
			idSubLinea:idSubLinea
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerSubLinea").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar la sublinea',500,5000,'error',30,3);
			$("#obtenerSubLinea").html('');
		}
	});
}

function editarSubLinea()
{
	mensaje="";
	
	if(!camposVacios($('#txtSubLinea').val()))
	{
		mensaje+="El nombre de la línea es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro?')) return;

	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#editandoSubLinea').html('<img src="'+ img_loader +'"/> Editando el registro...');
		},
		type:"POST",
		url:base_url+'configuracion/editarSubLinea',
		data:$('#frmSubLineas').serialize(),
		async: false,
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoSubLinea').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				
				break;
				
				case "1":
					obtenerSubLineas($('#txtIdLinea').val());
					notify('El registro ha sido exitoso',500,5000,'',30,5);
					$('#ventanaEditarSubLinea').dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar la línea',500,5000,'error',30,5);
			$("#editandoLinea").html('');
		}
	});		
}

function borrarSubLinea(idSubLinea)
{
	if(!confirm('¿Realmente desea editar el registro de la línea?')) return;

	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#procesandoSubLineas').html('<img src="'+ img_loader +'"/> Borrando el registro...');
		},
		type:"POST",
		url:base_url+'configuracion/borrarSubLinea',
		data:
		{
			idSubLinea:idSubLinea
		},
		async: false,
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoSubLineas').html('');
			data=eval(data);
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				
				break;
				
				case "1":
					obtenerSubLineas($('#txtIdLinea').val());
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al editar la línea',500,5000,'error',30,5);
			$("#procesandoSubLineas").html('');
		}
	});		
}