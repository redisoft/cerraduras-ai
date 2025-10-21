//LÍNEAS
function formularioLineas()
{
	$('#ventanaLineas').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#formularioLineas').html('<img src="'+ img_loader +'"/> Obteniendo los datos para registrar la línea..');
		},
		type:"POST",
		url:base_url+'configuracion/formularioLineas',
		data:
		{

		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioLineas").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para registrar la línea',500,5000,'error',30,3);
			$("#formularioLineas").html('');
		}
	});
}

function registrarLinea()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre de la línea es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	//document.forms['frmLineas'].submit();
	
	var formData = new FormData($('#frmLineas')[0]);
	
	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#registrandoLinea').html('<img src="'+ img_loader +'"/> Registrandos la línea, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/registrarLinea',
		data:formData,
		async: false,
		cache: false,
		contentType: false,
		processData: false, 
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
					window.location.href=base_url+'configuracion/lineas';
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

$(document).ready(function()
{
	$("#ventanaLineas").dialog(
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
				registrarLinea();
			},
		},
		close: function() 
		{
			$("#formularioLineas").html(''); 
		}
	});

	$("#ventanaEditarLinea").dialog(
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
				editarLinea();		  	  
			},
		},
		close: function() 
		{
			$('#obtenerLinea').html('');
		}
	});
});

function obtenerLinea(idLinea)
{
	$('#ventanaEditarLinea').dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerLinea').html('<img src="'+ img_loader +'"/> Obteniendo los datos para editar la línea..');
		},
		type:"POST",
		url:base_url+'configuracion/obtenerLinea',
		data:
		{
			idLinea:idLinea
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerLinea").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los datos para editar la línea',500,5000,'error',30,3);
			$("#obtenerLinea").html('');
		}
	});
}

function editarLinea()
{
	mensaje="";
	
	if($('#txtNombre').val()=="")
	{
		mensaje+="El nombre de la línea es incorrecto <br />";
	}

	if(mensaje.length>0)
	{
		notify(mensaje,500,5000,'error',30,5);
		return;
	}
	
	if(!confirm('¿Realmente desea editar el registro de la línea?')) return;
	
	//document.forms['frmLineas'].submit();
	var formData = new FormData($('#frmLineas')[0]);
	
	$.ajax(
	{
		beforeSend:function(objeto)
		{
			$('#editandoLinea').html('<img src="'+ img_loader +'"/> Editando la línea, por favor tenga paciencia..');
		},
		type:"POST",
		url:base_url+'configuracion/editarLinea',
		data:formData,
		async: false,
		cache: false,
		contentType: false,
		processData: false, 
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoLinea').html('');
			data=eval(data);
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				
				break;
				
				case "1":
					window.location.href=base_url+'configuracion/lineas';
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