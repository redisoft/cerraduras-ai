//UNIDADES
$(document).ready(function()
{
	$("#ventanaUnidades").dialog(
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
				registrarUnidad()
			},
		},
		close: function() 
		{
		}
	});
	
	$("#ventanaEditarUnidad").dialog(
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
				$('#obtenerUnidad').html('');			 
			},
			'Aceptar': function() 
			{
				editarUnidad();
			},
		},
		close: function() 
		{
			$('#obtenerUnidad').html('');
		}
	});
});

function formularioUnidades()
{
	$('#ventanaUnidades').dialog('open');
}

function registrarUnidad()
{
	if($('#txtNombreUnidad').val()=="")
	{
		notify('El nombre de la unidad es necesario',500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoUnidades').html('<img src="'+ img_loader +'"/>Registrando la unidad, por favor espere...');},
		type:"POST",
		url:base_url+"configuracion/registrarUnidad",
		data:
		{
			"descripcion":$("#txtNombreUnidad").val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoUnidades").html("");
			data=eval(data)
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					window.location.href=base_url+"configuracion/unidades";
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar la unidad',500,5000,'error',30,3);
			$("#registrandoUnidades").html("");	
		}
	});				  	  
}

function editarUnidad()
{
	if($('#txtUnidad').val()=="")
	{
		notify('El nombre de la unidad es necesario',500,5000,'error',30,3);
		return
	}
	
	if(!confirm('¿Realmente desea editar el registro de la unidad?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoUnidad').html('<img src="'+ img_loader +'"/> Editando unidad...');},
		type:"POST",
		url:base_url+'configuracion/editarUnidad',
		data:
		{
			"idUnidad":	$('#txtIdUnidad').val(),
			"nombre":	$('#txtUnidad').val()
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			switch(data)
			{
				case "0":
				notify('El registro de la unidad no se ha modificado',500,5000,'error',30,3);
				$('#editandoUnidad').html('');
				break;
				case "1":
				location.href=base_url+'configuracion/unidades';
				break;
			}
		},
		error:function(datos)
		{
			notify('El registro de la unidad no se ha modificado',500,5000,'error',30,3);
			$("#editandoUnidad").html('');
		}
	});
}

function obtenerUnidad(idUnidad)
{
	$("#ventanaEditarUnidad").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerUnidad').html('<img src="'+ img_loader +'"/> Obteniendo detalles de unidad...');},
		type:"POST",
		url:base_url+'configuracion/obtenerUnidad',
		data:
		{
			"idUnidad":idUnidad
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerUnidad").html(data);
		},
		error:function(datos)
		{
			$("#obtenerUnidad").html('Error al obtener los detalles de la unidad');
		}
	});
}