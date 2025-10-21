//CATEGORÍAS
$(document).ready(function()
{
	obtenerDepartamentos();
	
	$("#ventanaDepartamentos").dialog(
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
				registrarDepartamento()
			},
		},
		close: function() 
		{
			$('#formularioDepartamentos').html('');
		}
	});
	
	$("#ventanaEditarDepartamento").dialog(
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
				editarDepartamento();
			},
		},
		close: function() 
		{
			$('#obtenerDepartamento').html('');
		}
	});
});

function obtenerDepartamentos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerDepartamentos').html('<img src="'+ img_loader +'"/> Obteniendo detalles de departamentos...');},
		type:"POST",
		url:base_url+'catalogos/obtenerDepartamentos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDepartamentos").html(data);
		},
		error:function(datos)
		{
			$("#obtenerDepartamentos").html('');
		}
	});
}


function formularioDepartamentos()
{
	$("#ventanaDepartamentos").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#formularioDepartamentos').html('<img src="'+ img_loader +'"/> Obteniendo detalles de departamento...');},
		type:"POST",
		url:base_url+'catalogos/formularioDepartamentos',
		data:
		{
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#formularioDepartamentos").html(data);
		},
		error:function(datos)
		{
			$("#formularioDepartamentos").html('');
		}
	});
}

function registrarDepartamento()
{
	if(!camposVacios($('#txtDepartamento').val()))
	{
		notify('El nombre del departamento es necesario',500,5000,'error',30,5);
		return;
	}
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#registrandoDepartamento').html('<img src="'+ img_loader +'"/>Registrando el departamento, por favor espere...');},
		type:"POST",
		url:base_url+"catalogos/registrarDepartamento",
		data:$('#frmDepartamentos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#registrandoDepartamento").html("");
			
			data	= eval(data)
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,5);
				break;
				case "1":
					notify(data[1],500,5000,'',30,5);
					obtenerDepartamentos();
					$("#ventanaDepartamentos").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('Error al registrar el departamento',500,5000,'error',30,3);
			$("#registrandoDepartamento").html("");	
		}
	});				  	  
}

function obtenerDepartamento(idDepartamento)
{
	$("#ventanaEditarDepartamento").dialog('open');
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#obtenerDepartamento').html('<img src="'+ img_loader +'"/> Obteniendo detalles de departamento...');},
		type:"POST",
		url:base_url+'catalogos/obtenerDepartamento',
		data:
		{
			"idDepartamento":idDepartamento
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerDepartamento").html(data);
		},
		error:function(datos)
		{
			$("#obtenerDepartamento").html('Error al obtener los detalles del departamento');
		}
	});
}

function editarDepartamento()
{
	if(!camposVacios($('#txtDepartamento').val()))
	{
		notify('El nombre del departamento es necesario',500,5000,'error',30,3);
		return
	}
	
	if(!confirm('¿Realmente desea editar el registro del departamento?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#editandoDepartamento').html('<img src="'+ img_loader +'"/> Editando departamento...');},
		type:"POST",
		url:base_url+'catalogos/editarDepartamento',
		data:$('#frmDepartamentos').serialize(),
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#editandoDepartamento').html('');
			
			data	= eval(data)
			
			switch(data[0])
			{
				case "0":
					notify(data[1],500,5000,'error',30,3);
					
				break;
				case "1":
					notify(data[1],500,5000,'',30,5);
					obtenerDepartamentos();
					$("#ventanaEditarDepartamento").dialog('close');
				break;
			}
		},
		error:function(datos)
		{
			notify('El registro del departamento no se ha modificado',500,5000,'error',30,3);
			$("#editandoDepartamento").html('');
		}
	});
}

function borrarDepartamento(idDepartamento)
{
	if(!confirm('¿Realmente desea borrar el registro del departamento?'))return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto){$('#procesandoDepartamentos').html('<img src="'+ img_loader +'"/> Borrando departamento...');},
		type:"POST",
		url:base_url+'catalogos/borrarDepartamento',
		data:
		{
			idDepartamento:idDepartamento
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#procesandoDepartamentos').html('');

			switch(data[0])
			{
				case "0":
					notify('Error al borrar el registro',500,5000,'error',30,3);
					
				break;
				case "1":
					notify('El registro se ha borrado correctamente',500,5000,'',30,5);
					obtenerDepartamentos();
				break;
			}
		},
		error:function(datos)
		{
			notify('El registro del departamento no se ha modificado',500,5000,'error',30,3);
			$("#procesandoDepartamentos").html('');
		}
	});
}
